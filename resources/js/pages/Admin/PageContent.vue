<script setup lang="ts">
    import AppSidebar from '@/components/AppSidebar.vue';
    import FlashMessage from '@/components/Admin/FlashMessage.vue';
    import { Head, usePage } from '@inertiajs/vue3';
    import { 
        Save, 
        Image as ImageIcon, 
        Plus, 
        X, 
        UploadCloud,
        CheckCircle2,
        Info,
        Layout
    } from 'lucide-vue-next';
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    
    // Loader state
    const isLoading = ref(false);
    
    interface PageContent {
        id: number;
        welcome_image: string | null;
        about_us_image: string | null;
        vice_mayor_message: string;
        about_us: string;
        mission: string;
        vision: string;
        gallery_images: string[];
    }
    
    const { props } = usePage<{ pageContent: PageContent | null }>();
    
    // Form state
    const form = ref({
        welcome_image: null as File | null,
        about_us_image: null as File | null,
        gallery_images: [] as File[],
        vice_mayor_message: props.pageContent?.vice_mayor_message || '',
        about_us: props.pageContent?.about_us || '',
        mission: props.pageContent?.mission || '',
        vision: props.pageContent?.vision || '',
    });
    
    // Preview images for single uploads
    const previews = ref({
        welcome: props.pageContent?.welcome_image || null,
        about: props.pageContent?.about_us_image || null,
    });
    
    // Helper function to handle Object URLs safely in the template
    const getFilePreview = (file: File) => {
        return URL.createObjectURL(file);
    };
    
    // File handlers
    const handleFileChange = (e: Event, type: 'welcome' | 'about') => {
        const file = (e.target as HTMLInputElement).files?.[0];
        if (file) {
            if (type === 'about') {
                form.value.about_us_image = file; // Corrected key to match controller
            } else {
                form.value.welcome_image = file;
            }
            previews.value[type] = URL.createObjectURL(file);
        }
    };
    
    const handleGalleryChange = (e: Event) => {
        const files = Array.from((e.target as HTMLInputElement).files || []);
        form.value.gallery_images = [...form.value.gallery_images, ...files];
    };
    
    const removeNewGalleryItem = (index: number) => {
        form.value.gallery_images.splice(index, 1);
    };
    
    // Submit handler
    const submit = async () => {
        isLoading.value = true;
    
        const data = new FormData();
    
        // Append all form fields
        Object.entries(form.value).forEach(([k, v]) => {
            if (v instanceof File) {
                data.append(k, v);
            } else if (Array.isArray(v)) {
                v.forEach(file => data.append(`${k}[]`, file));
            } else if (v !== null) {
                data.append(k, String(v));
            }
        });
    
        const url = props.pageContent?.id
            ? `/page-content/${props.pageContent.id}`
            : `/page-content`;
    
        if (props.pageContent?.id) {
            data.append('_method', 'PUT'); // method spoofing for Laravel
        }
    
        router.visit(url, {
            method: 'post',
            data,
            forceFormData: true,
            preserveScroll: true,
            onFinish: () => {
                isLoading.value = false;
            }
        });
    };
    </script>
    
    <template>
        <Head title="CMS Management" />
        <div class="flex h-screen bg-slate-50">
            <AppSidebar />
            <main class="relative flex-1 overflow-auto">
                <FlashMessage />
    
                <div class="sticky top-0 z-20 border-b border-slate-200 bg-white shadow-md">
                    <div class="flex items-center justify-between px-8 py-6">
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-900">Page Content Management</h1>
                            <p class="mt-1 text-sm text-slate-600">
                                Update your website's landing page images and text content.
                            </p>
                        </div>
                        <button 
                            @click="submit"
                            :disabled="isLoading"
                            class="flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 hover:shadow-xl disabled:opacity-50"
                        >
                            <Save v-if="!isLoading" class="h-5 w-5"/>
                            <div v-else class="h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                            {{ props.pageContent ? 'Update Changes' : 'Save Content' }}
                        </button>
                    </div>
                </div>
    
                <div class="p-8 w-full">
                    <form @submit.prevent="submit" class="space-y-8">
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                                <ImageIcon class="h-5 w-5 text-emerald-600" />
                                <h2 class="font-bold text-slate-800">Primary Images</h2>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div v-for="type in (['welcome', 'about'] as const)" :key="type" class="group">
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">
                                        {{ type.replace('_', ' ') }} Image
                                    </label>
                                    <div class="relative aspect-video rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden transition-all group-hover:border-emerald-300">
                                        <img v-if="previews[type]" :src="previews[type]!" class="h-full w-full object-cover" />
                                        <div v-else class="flex flex-col items-center justify-center h-full text-slate-400">
                                            <UploadCloud class="h-8 w-8 mb-2" />
                                            <span class="text-xs">No image selected</span>
                                        </div>
                                        <label class="absolute inset-0 cursor-pointer flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <input type="file" class="hidden" @change="e => handleFileChange(e, type)" accept="image/*" />
                                            <span class="bg-white text-slate-900 px-4 py-2 rounded-lg text-sm font-bold shadow-xl">Change Photo</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-8">
                                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                                    <div class="flex items-center gap-2 mb-6">
                                        <Info class="h-5 w-5 text-sky-600" />
                                        <h2 class="font-bold text-slate-800">General Information</h2>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="text-sm font-bold text-slate-700">About Us Content</label>
                                            <textarea v-model="form.about_us" rows="4" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                        </div>
                                        <div>
                                            <label class="text-sm font-bold text-slate-700">Vice Mayor's Message</label>
                                            <textarea v-model="form.vice_mayor_message" rows="4" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                                <div class="flex items-center gap-2 mb-6">
                                    <span class="h-5 w-5 text-indigo-600"><Layout /></span>
                                    <h2 class="font-bold text-slate-800">Mandate & Core Values</h2>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-bold text-slate-700">Our Mission</label>
                                        <textarea v-model="form.mission" rows="4" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="text-sm font-bold text-slate-700">Our Vision</label>
                                        <textarea v-model="form.vision" rows="4" class="mt-2 w-full rounded-xl border-slate-200 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-2">
                                    <ImageIcon class="h-5 w-5 text-purple-600" />
                                    <h2 class="font-bold text-slate-800">Image Gallery</h2>
                                </div>
                                <label class="cursor-pointer flex items-center gap-2 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg transition-colors">
                                    <Plus class="h-4 w-4" />
                                    <span class="text-sm font-bold">Add Images</span>
                                    <input type="file" multiple class="hidden" @change="handleGalleryChange" />
                                </label>
                            </div>
    
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                                <div v-for="(file, idx) in form.gallery_images" :key="'new-' + idx" class="relative group aspect-square rounded-xl overflow-hidden border-2 border-emerald-400">
                                    <img :src="getFilePreview(file)" class="h-full w-full object-cover" />
                                    <button type="button" @click="removeNewGalleryItem(idx)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <X class="h-3 w-3" />
                                    </button>
                                    <div class="absolute bottom-0 inset-x-0 bg-emerald-500 text-[8px] text-white text-center py-0.5 font-bold uppercase">New</div>
                                </div>
    
                                <div v-for="(img, idx) in props.pageContent?.gallery_images" :key="'old-' + idx" class="relative aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                    <img :src="img" class="h-full w-full object-cover" />
                                    <div class="absolute top-1 right-1 bg-emerald-500 text-white rounded-full p-0.5 shadow-lg">
                                        <CheckCircle2 class="h-3 w-3" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </template>
    
    <style scoped>
    textarea {
        resize: none;
        transition: all 0.3s ease;
    }
    textarea:focus {
        background: #f8fafc;
    }
    </style>