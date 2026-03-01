<script setup lang="ts">
import { ref, watch } from 'vue';
import { 
  FileText, 
  MessageSquare, 
  IdCard, 
  Maximize2, 
  CheckCircle, 
  X 
} from 'lucide-vue-next';

// Define the shape of your Request object based on your snippet
interface OrdinanceDownloadRequest {
    id: number;
    status: 'pending' | 'approved' | 'rejected';
    purpose: string;
    created_at: string;
    valid_id_type: string;
    valid_id_url: string | null;
    user: {
        name: string;
        email: string;
        profile_photo: string | null;
        usertype: string;
        contact_number: string | null;
        birthdate: string | null;
        address: string | null;
    };
    ordinance: {
        ordinance_number: string;
        title_ordinances: string;
    };
}

const props = defineProps<{
    isOpen: boolean;
    viewingRequest: OrdinanceDownloadRequest | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'approve', request: OrdinanceDownloadRequest): void;
    (e: 'reject', request: OrdinanceDownloadRequest): void;
}>();

const isIdModalOpen = ref(false);

// Helper Functions
const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
};

const computeAge = (birthdate: string | null) => {
    if (!birthdate) return '??';
    const birth = new Date(birthdate);
    const diff = Date.now() - birth.getTime();
    return Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
};

const isImage = (url: string) => {
    return /\.(jpg|jpeg|png|webp|avif|gif)$/.test(url.toLowerCase());
};

// Prevent background scrolling
watch(() => props.isOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : 'auto';
});
</script>

<template>
    <div v-if="isOpen && viewingRequest" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="relative flex h-full max-h-[90vh] w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            
            <div class="flex items-center justify-between border-b border-slate-100 px-8 py-4">
                <h2 class="text-xl font-bold text-slate-800">Request Details</h2>
                <button @click="emit('close')" class="rounded-full p-2 hover:bg-slate-100 transition">
                    <X class="h-5 w-5 text-slate-500" />
                </button>
            </div>

            <div class="flex flex-1 flex-col overflow-y-auto md:flex-row">
                <div class="w-full border-r border-slate-100 bg-slate-50/50 p-8 md:w-80">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-4">
                            <img v-if="viewingRequest.user.profile_photo"
                                :src="`/storage/${viewingRequest.user.profile_photo}`"
                                class="h-32 w-32 rounded-2xl border-4 border-white object-cover shadow-md" />
                            <div v-else
                                class="flex h-32 w-32 items-center justify-center rounded-2xl border-4 border-white bg-gradient-to-br from-emerald-500 to-teal-600 text-3xl font-bold text-white shadow-md">
                                {{ getInitials(viewingRequest.user.name) }}
                            </div>
                            <span :class="[
                                'absolute -right-2 -bottom-2 rounded-lg px-2 py-1 text-[10px] font-bold tracking-wider uppercase shadow-sm',
                                viewingRequest.user.usertype === 'admin' ? 'bg-purple-600 text-white' : 'bg-blue-600 text-white',
                            ]">
                                {{ viewingRequest.user.usertype }}
                            </span>
                        </div>

                        <h3 class="text-lg leading-tight font-bold text-slate-900">{{ viewingRequest.user.name }}</h3>
                        <p class="text-sm text-slate-500">{{ viewingRequest.user.email }}</p>
                    </div>

                    <div class="mt-8 space-y-5">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold tracking-widest text-slate-400 uppercase">Contact</span>
                            <span class="text-sm font-medium text-slate-700">{{ viewingRequest.user.contact_number || 'None' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold tracking-widest text-slate-400 uppercase">Age / Birthdate</span>
                            <span class="text-sm font-medium text-slate-700">
                                {{ computeAge(viewingRequest.user.birthdate) }} yrs old ({{ viewingRequest.user.birthdate || 'N/A' }})
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold tracking-widest text-slate-400 uppercase">Address</span>
                            <span class="text-sm leading-relaxed font-medium text-slate-700">
                                {{ viewingRequest.user.address || 'No address provided' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 p-8">
                    <div :class="[
                        'mb-8 flex items-center justify-between rounded-xl border-l-4 px-6 py-4',
                        viewingRequest.status === 'pending' ? 'border-yellow-400 bg-yellow-50 text-yellow-800' : 
                        viewingRequest.status === 'approved' ? 'border-emerald-400 bg-emerald-50 text-emerald-800' : 
                        'border-red-400 bg-red-50 text-red-800',
                    ]">
                        <div>
                            <p class="text-[11px] font-bold tracking-widest uppercase opacity-70">Current Status</p>
                            <p class="text-lg font-bold capitalize">{{ viewingRequest.status }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-bold tracking-widest uppercase opacity-70">Requested Date</p>
                            <p class="font-semibold">{{ formatDate(viewingRequest.created_at) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                        <div>
                            <h4 class="mb-3 flex items-center gap-2 text-sm font-bold tracking-widest text-slate-400 uppercase">
                                <FileText class="h-4 w-4" /> Target Ordinance
                            </h4>
                            <div class="rounded-xl border border-slate-200 bg-white p-5 transition hover:border-emerald-200 hover:bg-emerald-50/30">
                                <p class="mb-1 text-xs font-bold text-emerald-600 uppercase">{{ viewingRequest.ordinance.ordinance_number }}</p>
                                <h5 class="text-base leading-snug font-bold text-slate-800">{{ viewingRequest.ordinance.title_ordinances }}</h5>
                            </div>

                            <h4 class="mt-6 mb-3 flex items-center gap-2 text-sm font-bold tracking-widest text-slate-400 uppercase">
                                <MessageSquare class="h-4 w-4" /> Reason
                            </h4>
                            <div class="relative rounded-xl bg-slate-100 p-5 text-slate-700 italic">
                                <p class="text-sm leading-relaxed whitespace-pre-line">{{ viewingRequest.purpose }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-3 flex items-center gap-2 text-sm font-bold tracking-widest text-slate-400 uppercase">
                                <IdCard class="h-4 w-4" /> Submitted Valid ID
                            </h4>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="mb-3 text-sm font-semibold text-slate-700">
                                    ID Type: <span class="font-bold text-emerald-600">{{ viewingRequest.valid_id_type }}</span>
                                </p>

                                <div v-if="viewingRequest.valid_id_url && isImage(viewingRequest.valid_id_url)"
                                    class="group relative cursor-pointer overflow-hidden rounded-lg border border-slate-300 bg-white shadow-sm"
                                    @click="isIdModalOpen = true">
                                    <img :src="viewingRequest.valid_id_url" class="h-40 w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                    <div class="absolute inset-0 flex items-center justify-center bg-slate-900/40 opacity-0 transition-opacity group-hover:opacity-100">
                                        <div class="flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-slate-900 shadow-lg">
                                            <Maximize2 class="h-3 w-3" /> Click to view full
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="viewingRequest.valid_id_url"
                                    class="flex h-40 flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-600">
                                    <FileText class="mb-2 h-8 w-8 text-slate-300" />
                                    <span>PDF file submitted. Preview not available.</span>
                                </div>
                                <div v-else
                                    class="flex h-40 items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-400">
                                    No valid ID uploaded.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-8 py-5">
                <button @click="emit('close')" class="rounded-lg px-6 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-200">
                    Close
                </button>
                <template v-if="viewingRequest.status === 'pending'">
                    <button @click="emit('reject', viewingRequest)" class="rounded-lg bg-red-50 px-6 py-2.5 text-sm font-bold text-red-600 transition hover:bg-red-100">
                        Deny Access
                    </button>
                    <button @click="emit('approve', viewingRequest)"
                        class="flex items-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 active:scale-95">
                        <CheckCircle class="h-4 w-4" /> Approve Request
                    </button>
                </template>
            </div>
        </div>

        <div v-if="isIdModalOpen" @click="isIdModalOpen = false" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90 p-4">
            <img :src="viewingRequest.valid_id_url!" class="max-h-full max-w-full rounded-lg object-contain shadow-2xl" />
            <button class="absolute top-6 right-6 text-white"><X class="h-8 w-8" /></button>
        </div>
    </div>
</template>