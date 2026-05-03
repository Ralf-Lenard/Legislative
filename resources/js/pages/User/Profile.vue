<template>
    <Head title="Profile Settings" />
    <div class="min-h-screen bg-white">
        <Navbar />

        <FlashMessage />

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gray-50 px-4 pt-28 pb-16">
            <div
                class="absolute top-0 right-0 h-[400px] w-[400px] rounded-full bg-yellow-400/20 blur-3xl"
            ></div>
            <div
                class="absolute bottom-0 left-0 h-[300px] w-[300px] rounded-full bg-green-900/10 blur-3xl"
            ></div>

            <div class="relative z-10 mx-auto max-w-5xl">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-2 text-xs font-bold tracking-widest text-green-900 uppercase"
                >
                    Account Settings
                </span>
                <h1 class="mt-4 text-5xl font-black text-gray-900">
                    Personal <span class="text-green-800">Profile</span>
                </h1>
            </div>
        </section>

        <!-- Content Section -->
        <section class="px-4 py-12">
            <div
                class="mx-auto grid max-w-5xl grid-cols-1 gap-10 lg:grid-cols-3"
            >
                <!-- Sidebar / Photo -->
                <div class="space-y-6">
                    <div
                        class="relative overflow-hidden rounded-3xl border border-gray-100 bg-white p-8 text-center shadow-xl"
                    >
                        <div
                            class="absolute top-0 left-0 h-24 w-full bg-gradient-to-r from-green-900 to-green-700"
                        ></div>

                        <div
                            class="group relative mx-auto mt-8 h-32 w-32 cursor-pointer"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop"
                            @click="$refs.fileInput.click()"
                        >
                            <div
                                :class="[
                                    'h-full w-full overflow-hidden rounded-full border-4 border-white shadow-2xl ring-4 transition-all duration-300',
                                    isDragging
                                        ? 'scale-105 ring-yellow-400'
                                        : 'ring-transparent',
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
                                    class="flex h-full w-full items-center justify-center bg-green-900 text-4xl font-black text-white"
                                >
                                    {{ initials }}
                                </div>
                            </div>

                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center rounded-full bg-black/40 text-[10px] font-bold tracking-tighter text-white uppercase opacity-0 transition-opacity group-hover:opacity-100"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="mb-1 h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                Change Photo
                            </div>

                            <input
                                type="file"
                                ref="fileInput"
                                class="hidden"
                                accept="image/*"
                                @change="handlePhoto"
                            />
                        </div>

                        <h3 class="mt-6 text-2xl font-black text-gray-900">
                            {{ user.name }}
                        </h3>
                        <p
                            class="mt-1 inline-block rounded-full bg-green-50 px-3 py-1 text-sm font-medium text-green-700"
                        >
                            {{ user.email }}
                        </p>

                        <div
                            class="mt-8 space-y-4 border-t border-gray-50 pt-6 text-left"
                        >
                            <div class="flex items-center gap-3 text-gray-600">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-green-900"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        />
                                    </svg>
                                </div>
                                <span class="truncate text-sm">{{
                                    user.contact_number || 'No contact info'
                                }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-gray-600">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-green-900"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                                <span class="truncate text-sm">{{
                                    user.address || 'No address provided'
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="photoPreview"
                        class="flex animate-pulse items-start gap-3 rounded-2xl border border-yellow-200 bg-yellow-50 p-4"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-5 w-5 text-yellow-600"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <p class="text-xs font-bold text-yellow-800">
                            Don't forget to click "Save Changes" to upload your
                            new photo!
                        </p>
                    </div>
                </div>

                <!-- Forms -->
                <div class="space-y-8 lg:col-span-2">
                    <div
                        class="rounded-3xl border border-gray-100 bg-white p-8 shadow-lg"
                    >
                        <h2
                            class="mb-8 flex items-center gap-3 text-2xl font-black text-gray-900"
                        >
                            <span
                                class="h-8 w-2 rounded-full bg-green-900"
                            ></span>
                            Profile Information
                        </h2>

                        <form
                            @submit.prevent="updateProfile"
                            class="grid grid-cols-1 gap-6 md:grid-cols-2"
                        >
                            <div>
                                <label class="label">Full Name</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="input"
                                    placeholder="John Doe"
                                />
                            </div>

                            <div>
                                <label class="label">Email Address</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="input"
                                    placeholder="john@example.com"
                                />
                            </div>

                            <div>
                                <label class="label">Contact Number</label>
                                <input
                                    v-model="form.contact_number"
                                    type="text"
                                    class="input"
                                    placeholder="+63 9xx xxx xxxx"
                                />
                            </div>

                            <div>
                                <label class="label">Birthdate</label>
                                <input
                                    v-model="form.birthdate"
                                    type="date"
                                    class="input"
                                />
                            </div>

                            <div class="md:col-span-2">
                                <label class="label">Complete Address</label>
                                <input
                                    v-model="form.address"
                                    type="text"
                                    class="input"
                                    placeholder="Street, Barangay, City"
                                />
                            </div>

                            <div
                                class="mt-4 flex justify-end border-t border-gray-50 pt-4 md:col-span-2"
                            >
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="btn-primary group"
                                >
                                    <span v-if="form.processing"
                                        >Processing...</span
                                    >
                                    <span
                                        v-else
                                        class="flex items-center gap-2"
                                    >
                                        Save Changes
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 transition-transform group-hover:translate-x-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Section -->
                    <div
                        class="rounded-3xl border border-gray-100 bg-white p-8 shadow-lg"
                    >
                        <h2
                            class="mb-8 flex items-center gap-3 text-2xl font-black text-gray-900"
                        >
                            <span
                                class="h-8 w-2 rounded-full bg-yellow-400"
                            ></span>
                            Security
                        </h2>

                        <form
                            @submit.prevent="updatePassword"
                            class="grid grid-cols-1 gap-6 md:grid-cols-2"
                        >
                            <div class="relative md:col-span-2">
                                <label class="label">Current Password</label>
                                <div class="relative">
                                    <input
                                        v-model="passwordForm.current_password"
                                        :type="
                                            showCurrent ? 'text' : 'password'
                                        "
                                        class="input pr-12"
                                    />
                                    <button
                                        type="button"
                                        @click="showCurrent = !showCurrent"
                                        class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-400 transition-colors hover:text-green-900"
                                    >
                                        <component
                                            :is="
                                                showCurrent
                                                    ? EyeSlashIcon
                                                    : EyeIcon
                                            "
                                            class="h-5 w-5"
                                        />
                                    </button>
                                </div>
                            </div>

                            <div class="relative">
                                <label class="label">New Password</label>
                                <div class="relative">
                                    <input
                                        v-model="passwordForm.password"
                                        :type="showNew ? 'text' : 'password'"
                                        class="input pr-12"
                                    />
                                    <button
                                        type="button"
                                        @click="showNew = !showNew"
                                        class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-400 transition-colors hover:text-green-900"
                                    >
                                        <component
                                            :is="
                                                showNew ? EyeSlashIcon : EyeIcon
                                            "
                                            class="h-5 w-5"
                                        />
                                    </button>
                                </div>
                            </div>

                            <div class="relative">
                                <label class="label"
                                    >Confirm New Password</label
                                >
                                <div class="relative">
                                    <input
                                        v-model="
                                            passwordForm.password_confirmation
                                        "
                                        :type="
                                            showConfirm ? 'text' : 'password'
                                        "
                                        class="input pr-12"
                                    />
                                    <button
                                        type="button"
                                        @click="showConfirm = !showConfirm"
                                        class="absolute top-1/2 right-4 -translate-y-1/2 text-gray-400 transition-colors hover:text-green-900"
                                    >
                                        <component
                                            :is="
                                                showConfirm
                                                    ? EyeSlashIcon
                                                    : EyeIcon
                                            "
                                            class="h-5 w-5"
                                        />
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4 md:col-span-2">
                                <button
                                    type="submit"
                                    :disabled="passwordForm.processing"
                                    class="btn-secondary"
                                >
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <Footer />
    </div>
</template>

<script setup lang="ts">
import FlashMessage from '@/components/FlashMessage.vue';
import Footer from '@/components/Home/Footer.vue';
import Navbar from '@/components/Home/Navbar.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Heroicons for View Password
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';

const props = defineProps<{
    user: {
        name: string;
        email: string;
        address?: string;
        contact_number?: string;
        birthdate?: string;
        profile_photo?: string;
    };
}>();

// View Password States
const showCurrent = ref(false);
const showNew = ref(false);
const showConfirm = ref(false);

const photoPreview = ref<string | null>(null);
const isDragging = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

const initials = computed(() =>
    props.user.name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .substring(0, 2),
);

const profilePhotoFullUrl = computed(() => {
    if (!props.user.profile_photo) return null;
    const cleanPath = props.user.profile_photo.replace('/storage/', '');
    return `/storage/${cleanPath}`;
});

const handleImageError = (e: Event) => {
    const target = e.target as HTMLImageElement;
    target.src = `https://ui-avatars.com/api/?name=${props.user.name}&background=14532d&color=fff`;
};

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    address: props.user.address ?? '',
    contact_number: props.user.contact_number ?? '',
    birthdate: props.user.birthdate ?? '',
    profile_photo: null as File | null,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// File Handlers
const handleDrop = (e: DragEvent) => {
    isDragging.value = false;
    const file = e.dataTransfer?.files[0];
    if (file && file.type.startsWith('image/')) {
        processFile(file);
    }
};

const handlePhoto = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) processFile(file);
};

const processFile = (file: File) => {
    form.profile_photo = file;
    photoPreview.value = URL.createObjectURL(file);
};

const updateProfile = async () => {
    const data = new FormData();
    Object.entries(form).forEach(([key, val]) => {
        if (val instanceof File) {
            data.append(key, val);
        } else if (val !== null) {
            data.append(key, String(val));
        }
    });
    data.append('_method', 'PUT');

    router.visit('/profile/update', {
        method: 'post',
        data,
        forceFormData: true,
        preserveState: false,
        preserveScroll: true,
        onFinish: () => {
            photoPreview.value = null;
            form.profile_photo = null;
        },
    });
};

const updatePassword = async () => {
    const data = new FormData();
    Object.entries(passwordForm).forEach(([key, val]) => {
        if (val !== null) data.append(key, String(val));
    });
    data.append('_method', 'PUT');

    router.visit('/user/profile/password', {
        method: 'post',
        data,
        preserveScroll: true,
        preserveState: false,
        onFinish: () => {
            passwordForm.reset();
            showCurrent.value = false;
            showNew.value = false;
            showConfirm.value = false;
        },
    });
};
</script>

<style scoped>
@reference "tailwindcss";

.input {
    /* Added text-gray-900 to fix the visibility issue */
    @apply w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 transition-all outline-none placeholder:text-gray-400 focus:border-green-900 focus:bg-white focus:ring-4 focus:ring-green-900/10;
}

.label {
    @apply mb-2 ml-1 block text-xs font-black tracking-widest text-gray-500 uppercase;
}

.btn-primary {
    @apply rounded-2xl bg-green-900 px-10 py-4 font-black text-white transition-all hover:bg-green-800 hover:shadow-2xl hover:shadow-green-900/30 disabled:opacity-50;
}

.btn-secondary {
    @apply rounded-2xl bg-yellow-400 px-10 py-4 font-black text-green-900 transition-all hover:bg-yellow-300 hover:shadow-2xl hover:shadow-yellow-400/30 disabled:opacity-50;
}
</style>