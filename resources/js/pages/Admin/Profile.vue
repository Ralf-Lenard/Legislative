<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Camera, CheckCircle, Lock, User, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

/* =======================
       TYPES
    ======================= */

interface UserProfile {
    id: number;
    name: string;
    email: string;
    contact_number?: string;
    birthdate?: string;
    address?: string;
    profile_photo?: string | null;
}

/* =======================
       PAGE PROPS
    ======================= */

const page = usePage<{ auth: { user: UserProfile } }>();
const user = computed(() => page.props.auth?.user);

/* =======================
       STATE
    ======================= */

const flashMessage = ref<{ type: 'success' | 'error'; text: string } | null>(
    null,
);

const form = ref({
    name: user.value?.name || '',
    email: user.value?.email || '',
    contact_number: user.value?.contact_number || '',
    birthdate: user.value?.birthdate || '',
    address: user.value?.address || '',
    profile_photo: null as File | null,
});

const passwordForm = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const photoPreview = ref<string | null>(null);
const isDragging = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

const initials = computed(() => {
    if (!form.value.name) return 'A';
    return form.value.name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .substring(0, 2)
        .toUpperCase();
});

const profilePhotoFullUrl = computed(() => {
    if (!user.value?.profile_photo) return null;
    const cleanPath = user.value.profile_photo.replace('/storage/', '');
    return `/storage/${cleanPath}`;
});

/* =======================
       METHODS
    ======================= */

const handleDrop = (e: DragEvent) => {
    isDragging.value = false;
    const file = e.dataTransfer?.files[0];
    if (file && file.type.startsWith('image/')) processFile(file);
};

const onPhotoSelected = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) processFile(file);
};

const processFile = (file: File) => {
    form.value.profile_photo = file;
    photoPreview.value = URL.createObjectURL(file);
};

const handleImageError = (e: Event) => {
    (e.target as HTMLImageElement).style.display = 'none';
};

const updateProfile = () => {
    const data = new FormData();
    data.append('name', form.value.name);
    data.append('email', form.value.email);
    data.append('contact_number', form.value.contact_number ?? '');
    data.append('birthdate', form.value.birthdate ?? '');
    data.append('address', form.value.address ?? '');
    if (form.value.profile_photo)
        data.append('profile_photo', form.value.profile_photo);

    router.post('/admin/profile/update', data, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            photoPreview.value = null;
            form.value.profile_photo = null;
            flashMessage.value = {
                type: 'success',
                text: 'Profile updated successfully!',
            };
            setTimeout(() => (flashMessage.value = null), 4000);
        },
    });
};

const updatePassword = () => {
    router.post(
        '/admin/profile/update-password',
        {
            current_password: passwordForm.value.current_password,
            password: passwordForm.value.password,
            password_confirmation: passwordForm.value.password_confirmation,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                passwordForm.value.current_password = '';
                passwordForm.value.password = '';
                passwordForm.value.password_confirmation = '';
                flashMessage.value = {
                    type: 'success',
                    text: 'Password updated successfully!',
                };
                setTimeout(() => (flashMessage.value = null), 4000);
            },
        },
    );
};
</script>
<template>
    <Head title="Profile Settings" />
    <div class="flex h-screen bg-slate-50 text-slate-900">
        <AppSidebar />

        <main class="relative flex-1 overflow-auto">
            <!-- Flash Message -->
            <transition name="fade-slide">
                <div
                    v-if="flashMessage"
                    :class="
                        flashMessage.type === 'error'
                            ? 'bg-red-600'
                            : 'bg-emerald-600'
                    "
                    class="fixed top-4 right-4 z-[9999] flex items-center gap-2 rounded-lg px-5 py-3 text-sm font-medium text-white shadow-xl"
                >
                    <CheckCircle
                        v-if="flashMessage.type === 'success'"
                        class="h-5 w-5"
                    />
                    <X v-else class="h-5 w-5" />
                    {{ flashMessage.text }}
                </div>
            </transition>

            <!-- Page Header -->
            <div
                class="sticky top-0 z-40 border-b border-slate-200 bg-white px-8 py-6 shadow-md"
            >
                <h1 class="text-3xl font-extrabold text-slate-900">
                    Profile Settings
                </h1>
                <p class="mt-1 text-sm text-slate-600">
                    Update your account credentials and public profile info.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 p-8 lg:grid-cols-3">
                <!-- Profile Card -->
                <div
                    class="relative col-span-1 rounded-xl bg-white p-6 text-center shadow-lg border border-slate-100"
                >
                    <div
                        class="relative mx-auto h-40 w-40 cursor-pointer"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop"
                        @click="$refs.fileInput.click()"
                    >
                        <div
                            :class="[
                                'h-full w-full overflow-hidden rounded-xl border-4 border-white shadow-xl ring-2 transition-all duration-300',
                                isDragging
                                    ? 'scale-105 ring-emerald-500'
                                    : 'ring-slate-100',
                            ]"
                        >
                            <img
                                v-if="photoPreview || profilePhotoFullUrl"
                                :src="photoPreview || profilePhotoFullUrl"
                                class="h-full w-full object-cover"
                                @error="handleImageError"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-emerald-600 text-5xl font-black text-white"
                            >
                                {{ initials }}
                            </div>
                        </div>
                        <div
                            class="absolute -right-2 -bottom-2 rounded-xl border-2 border-white bg-emerald-500 p-2.5 text-white shadow-lg transition-transform group-hover:scale-110"
                        >
                            <Camera class="h-5 w-5" />
                        </div>
                        <input
                            type="file"
                            ref="fileInput"
                            class="hidden"
                            accept="image/*"
                            @change="onPhotoSelected"
                        />
                    </div>
                    <h3 class="mt-6 text-xl font-bold text-slate-900">
                        {{ form.name }}
                    </h3>
                    <span
                        class="mt-2 inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold tracking-wider text-emerald-700 uppercase"
                    >
                        System Administrator
                    </span>
                    <p
                        v-if="photoPreview"
                        class="mt-4 animate-pulse text-[10px] font-bold tracking-widest text-amber-600 uppercase"
                    >
                        New photo selected! Please save.
                    </p>
                </div>

                <!-- Profile Form -->
                <div class="col-span-2 space-y-6">
                    <div class="rounded-xl bg-white p-6 shadow-lg border border-slate-100">
                        <h2
                            class="mb-6 flex items-center gap-3 text-xl font-bold text-slate-900"
                        >
                            <User class="h-6 w-6 text-emerald-600" /> Profile Information
                        </h2>
                        <form
                            @submit.prevent="updateProfile"
                            class="grid grid-cols-1 gap-6 md:grid-cols-2"
                        >
                            <div>
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >Full Name</label
                                >
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>
                            <div>
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >Email Address</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>
                            <div>
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >Contact Number</label
                                >
                                <input
                                    v-model="form.contact_number"
                                    type="text"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>
                            <div>
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >Birthdate</label
                                >
                                <input
                                    v-model="form.birthdate"
                                    type="date"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >Office Address</label
                                >
                                <textarea
                                    v-model="form.address"
                                    rows="2"
                                    class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                ></textarea>
                            </div>
                            <div class="flex justify-end md:col-span-2">
                                <button
                                    type="submit"
                                    class="rounded-xl bg-emerald-600 px-6 py-2 font-bold text-white transition-all hover:bg-emerald-700 shadow-md hover:shadow-lg"
                                >
                                    Save Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Password Form -->
                    <div class="rounded-xl bg-white p-6 shadow-lg border border-slate-100">
                        <h2
                            class="mb-6 flex items-center gap-3 text-xl font-bold text-slate-900"
                        >
                            <Lock class="h-6 w-6 text-slate-700" /> Change Password
                        </h2>
                        <form
                            @submit.prevent="updatePassword"
                            class="grid grid-cols-1 gap-6 md:grid-cols-2"
                        >
                            <div class="md:col-span-2">
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >Current Password</label
                                >
                                <input
                                    v-model="passwordForm.current_password"
                                    type="password"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>
                            <div>
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >New Password</label
                                >
                                <input
                                    v-model="passwordForm.password"
                                    type="password"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>
                            <div>
                                <label
                                    class="mb-2 ml-1 block text-xs font-black tracking-widest text-slate-400 uppercase"
                                    >Confirm Password</label
                                >
                                <input
                                    v-model="passwordForm.password_confirmation"
                                    type="password"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 focus:outline-none"
                                />
                            </div>
                            <div class="flex justify-end md:col-span-2">
                                <button
                                    type="submit"
                                    class="rounded-xl bg-slate-900 px-6 py-2 font-bold text-white transition-all hover:bg-slate-800 shadow-md"
                                >
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
main::-webkit-scrollbar {
    width: 6px;
}
main::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 10px;
}
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s ease;
}
.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
