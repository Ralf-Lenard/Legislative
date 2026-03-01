<template>
    <Head title="My Document Requests" />
    <div class="min-h-screen bg-white">
        <Navbar />
        <FlashMessage />

        <section class="relative overflow-hidden bg-gray-50 px-4 pt-24 pb-12 md:pt-28 md:pb-16">
            <div class="absolute top-0 right-0 h-[300px] w-[300px] md:h-[400px] md:w-[400px] rounded-full bg-yellow-400/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-[200px] w-[200px] md:h-[300px] md:w-[300px] rounded-full bg-green-900/10 blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-5xl text-center md:text-left">
                <span class="inline-block rounded-full bg-green-100 px-4 py-2 text-[10px] md:text-xs font-bold tracking-widest text-green-900 uppercase">
                    Track History
                </span>
                <h1 class="mt-4 text-3xl md:text-5xl font-black text-gray-900 leading-tight">
                    Document <span class="text-green-800">Requests</span>
                </h1>
                <p class="mt-4 max-w-2xl mx-auto md:mx-0 text-sm md:text-base text-gray-600 font-medium">
                    Monitor the status of your requested ordinances and resolutions.
                </p>
            </div>
        </section>

        <section class="px-4 py-8 md:py-12">
            <div class="mx-auto max-w-5xl">
                <div class="space-y-4 md:hidden">
                    <div v-for="(request, index) in requests" :key="'mob-' + index" 
                         class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-2">
                                <span :class="['h-2 w-2 rounded-full', request.type === 'Ordinance' ? 'bg-blue-500' : 'bg-purple-500']"></span>
                                <span class="text-[10px] font-black uppercase text-gray-400">{{ request.type }}</span>
                            </div>
                            <span :class="getStatusStyles(request.status)" class="rounded-full px-3 py-0.5 text-[9px] font-black uppercase tracking-wider">
                                {{ request.status }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-gray-900 leading-snug mb-2">{{ request.title }}</h3>
                        
                        <div class="flex items-start gap-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mt-0.5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-gray-500 italic line-clamp-2">{{ request.purpose }}</p>
                        </div>

                        <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Requested On:</span>
                            <span class="text-[10px] text-gray-900 font-bold uppercase tracking-tighter">{{ request.created_at }}</span>
                        </div>
                    </div>
                    
                    <div v-if="requests.length === 0" class="text-center py-12 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                        <p class="text-sm font-bold text-gray-400 uppercase">No requests found</p>
                    </div>
                </div>

                <div class="hidden md:block rounded-3xl border border-gray-100 bg-white shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-green-900 text-white font-black uppercase text-xs tracking-widest">
                                    <th class="px-8 py-6">Category</th>
                                    <th class="px-8 py-6">Document Title</th>
                                    <th class="px-8 py-6">Purpose</th>
                                    <th class="px-8 py-6 text-center">Status</th>
                                    <th class="px-8 py-6">Date Requested</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="(request, index) in requests" :key="'desk-' + index" class="group transition-colors hover:bg-gray-50/50">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span :class="['h-2 w-2 rounded-full', request.type === 'Ordinance' ? 'bg-blue-500' : 'bg-purple-500']"></span>
                                            <span class="text-[11px] font-black uppercase tracking-tighter text-gray-400 group-hover:text-gray-900">
                                                {{ request.type }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="font-bold text-gray-900 line-clamp-2 max-w-xs leading-tight">
                                            {{ request.title }}
                                        </p>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-start gap-2 max-w-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm text-gray-500 italic line-clamp-2">
                                                {{ request.purpose }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span :class="getStatusStyles(request.status)" class="inline-block rounded-full px-4 py-1 text-[10px] font-black uppercase tracking-widest">
                                            {{ request.status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">
                                            {{ request.created_at }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-if="requests.length === 0" class="px-8 py-24 text-center">
                            <div class="mx-auto flex flex-col items-center max-w-xs">
                                <div class="mb-4 rounded-full bg-gray-50 p-6 text-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-black text-gray-900">No Requests Found</h3>
                                <p class="text-sm text-gray-400 font-medium">You haven't requested any documents yet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <Footer />
    </div>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/Home/Navbar.vue';
import Footer from '@/components/Home/Footer.vue';
import FlashMessage from '@/components/FlashMessage.vue';

const props = defineProps<{
    requests: Array<{
        type: string;
        title: string;
        status: string;
        purpose: string;
        created_at: string;
    }>;
}>();

const getStatusStyles = (status: string) => {
    const s = status?.toLowerCase() || '';
    if (s.includes('pending')) return 'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200';
    if (s.includes('approved') || s.includes('completed')) return 'bg-green-100 text-green-700 ring-1 ring-green-200';
    if (s.includes('rejected') || s.includes('denied')) return 'bg-red-100 text-red-700 ring-1 ring-red-200';
    return 'bg-gray-100 text-gray-600 ring-1 ring-gray-200';
};
</script>

<style scoped>
@reference "tailwindcss";

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;  
    overflow: hidden;
}
</style>