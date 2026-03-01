<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { Head } from '@inertiajs/vue3';
import {
    Activity,
    BarChart3,
    ClipboardList,
    Clock,
    FileText,
    TrendingUp,
    User,
    UserCog,
    Users,
    Trophy
} from 'lucide-vue-next';

import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip,
    Filler
} from 'chart.js';
import { Bar, Line } from 'vue-chartjs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

const props = defineProps<{
    role: string;
    stats: { totalOrdinances: number; totalResolutions: number; };
    ordinanceRequestStatus: { pending: number; approved: number; rejected: number; };
    resolutionRequestStatus: { pending: number; approved: number; rejected: number; };
    monthlyRequests: Array<{ month: string; ordinances: number; resolutions: number; }>;
    // New Props from Controller
    topOrdinances: Array<{ title: string; total: number; }>;
    topResolutions: Array<{ title: string; total: number; }>;
    // Super Admin Props
    userStats?: { totalUsers: number; admins: number; users: number; } | null;
    userMonthly?: Array<{ month: string; total: number; }> | null;
}>();

/* =======================
    CHART CONFIGURATIONS
======================= */

// 1. Monthly Trends (Line Chart)
const lineChartData = {
    labels: props.monthlyRequests.map((m) => m.month),
    datasets: [
        {
            label: 'Ordinances',
            data: props.monthlyRequests.map((m) => m.ordinances),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4,
        },
        {
            label: 'Resolutions',
            data: props.monthlyRequests.map((m) => m.resolutions),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
        },
    ],
};

// 2. Request Status (Bar Chart)
const statusChartData = {
    labels: ['Pending', 'Approved', 'Rejected'],
    datasets: [
        {
            label: 'Ordinances',
            backgroundColor: '#10b981',
            borderRadius: 6,
            data: [
                props.ordinanceRequestStatus.pending,
                props.ordinanceRequestStatus.approved,
                props.ordinanceRequestStatus.rejected,
            ],
        },
        {
            label: 'Resolutions',
            backgroundColor: '#3b82f6',
            borderRadius: 6,
            data: [
                props.resolutionRequestStatus.pending,
                props.resolutionRequestStatus.approved,
                props.resolutionRequestStatus.rejected,
            ],
        },
    ],
};

// 3. Top Requested Ordinances (Horizontal Bar)
const topOrdinancesData = {
    labels: props.topOrdinances.map(o => o.title.substring(0, 20) + (o.title.length > 20 ? '...' : '')),
    datasets: [{
        label: 'Requests',
        backgroundColor: '#10b981',
        data: props.topOrdinances.map(o => o.total),
        borderRadius: 4,
    }]
};

// 4. Top Requested Resolutions (Horizontal Bar)
const topResolutionsData = {
    labels: props.topResolutions.map(r => r.title.substring(0, 20) + (r.title.length > 20 ? '...' : '')),
    datasets: [{
        label: 'Requests',
        backgroundColor: '#3b82f6',
        data: props.topResolutions.map(r => r.total),
        borderRadius: 4,
    }]
};

// 5. User Growth (Super Admin Only)
const userChartData = props.userMonthly && props.role === 'super_admin' ? {
    labels: props.userMonthly.map((u) => u.month),
    datasets: [{
        label: 'New Registrations',
        data: props.userMonthly.map((u) => u.total),
        borderColor: '#8b5cf6',
        backgroundColor: 'rgba(139, 92, 246, 0.1)',
        fill: true,
        tension: 0.4,
    }],
} : null;

// Chart Options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { 
            position: 'top' as const,
            align: 'end' as const,
            labels: { boxWidth: 8, usePointStyle: true, font: { size: 11 } } 
        },
    },
    scales: {
        y: { beginAtZero: true, grid: { display: false } },
        x: { grid: { display: false } }
    }
};

const horizontalOptions = {
    ...chartOptions,
    indexAxis: 'y' as const,
    scales: {
        x: { beginAtZero: true, grid: { display: false } },
        y: { grid: { display: false } }
    }
};

const totalPending = props.ordinanceRequestStatus.pending + props.resolutionRequestStatus.pending;
const totalRequests = props.monthlyRequests.reduce((sum, m) => sum + m.ordinances + m.resolutions, 0);
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="flex h-screen bg-[#f8fafc]">
        <AppSidebar />

        <main class="flex-1 overflow-y-auto">
            <header class="sticky top-0 z-30 bg-white/80 border-b border-slate-200 backdrop-blur-md px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Legislative Dashboard</h1>
                        <p class="text-xs font-medium text-slate-500 flex items-center gap-2">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live System Overview • {{ new Date().toLocaleDateString() }}
                        </p>
                    </div>
                    
                    <div v-if="totalPending > 0" class="flex items-center gap-2 px-3 py-1 bg-amber-50 border border-amber-100 rounded-full">
                        <Clock class="w-3.5 h-3.5 text-amber-600" />
                        <span class="text-xs font-bold text-amber-700">{{ totalPending }} Action Items</span>
                    </div>
                </div>
            </header>

            <div class="p-8 space-y-10">
                <section v-if="role === 'super_admin'" class="space-y-6">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-1 bg-violet-600 rounded-full"></div>
                            <h2 class="font-bold text-slate-800 uppercase tracking-widest text-xs">User Management System</h2>
                        </div>
    
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="p-2 bg-violet-50 text-violet-600 rounded-xl group-hover:bg-violet-600 group-hover:text-white transition-colors">
                                        <Users class="w-5 h-5" />
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded">TOTAL</span>
                                </div>
                                <p class="text-3xl font-black text-slate-900">{{ userStats?.totalUsers }}</p>
                                <p class="text-xs font-medium text-slate-500 mt-1">Registered Platform Users</p>
                            </div>
    
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4 text-emerald-600">
                                    <div class="p-2 bg-emerald-50 rounded-xl"><UserCog class="w-5 h-5" /></div>
                                    <span class="text-[10px] font-bold text-slate-400">STAFF</span>
                                </div>
                                <p class="text-3xl font-black text-slate-900">{{ userStats?.admins }}</p>
                                <p class="text-xs font-medium text-slate-500 mt-1">Authorized Administrators</p>
                            </div>
    
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4 text-blue-600">
                                    <div class="p-2 bg-blue-50 rounded-xl"><User class="w-5 h-5" /></div>
                                    <span class="text-[10px] font-bold text-slate-400">CITIZENS</span>
                                </div>
                                <p class="text-3xl font-black text-slate-900">{{ userStats?.users }}</p>
                                <p class="text-xs font-medium text-slate-500 mt-1">Public Account Access</p>
                            </div>
                        </div>
    
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="mb-6 flex items-center justify-between">
                                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                    <TrendingUp class="w-4 h-4 text-violet-600" />
                                    User Registration Growth
                                </h3>
                            </div>
                            <div class="h-[280px]">
                                <Line v-if="userChartData" :data="userChartData" :options="chartOptions" />
                            </div>
                        </div>
                    </section>

                 <section class="space-y-6">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-1 bg-emerald-600 rounded-full"></div>
                            <h2 class="font-bold text-slate-800 uppercase tracking-widest text-xs">Legislative Inventory</h2>
                        </div>
    
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-white p-6 rounded-2xl border-b-4 border-b-emerald-500 shadow-sm">
                                <p class="text-xs font-bold text-slate-400 uppercase">Ordinances</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-3xl font-black text-slate-900">{{ stats.totalOrdinances }}</p>
                                    <FileText class="text-emerald-100 w-8 h-8" />
                                </div>
                            </div>
    
                            <div class="bg-white p-6 rounded-2xl border-b-4 border-b-blue-500 shadow-sm">
                                <p class="text-xs font-bold text-slate-400 uppercase">Resolutions</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-3xl font-black text-slate-900">{{ stats.totalResolutions }}</p>
                                    <ClipboardList class="text-blue-100 w-8 h-8" />
                                </div>
                            </div>
    
                            <div class="bg-white p-6 rounded-2xl border-b-4 border-b-amber-500 shadow-sm">
                                <p class="text-xs font-bold text-slate-400 uppercase">Pending Review</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-3xl font-black text-slate-900">{{ totalPending }}</p>
                                    <Clock class="text-amber-100 w-8 h-8" />
                                </div>
                            </div>
    
                            <div class="bg-white p-6 rounded-2xl border-b-4 border-b-slate-800 shadow-sm">
                                <p class="text-xs font-bold text-slate-400 uppercase">Total Activity</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-3xl font-black text-slate-900">{{ totalRequests }}</p>
                                    <Activity class="text-slate-100 w-8 h-8" />
                                </div>
                            </div>
                        </div>
                    </section>

                <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <BarChart3 class="w-4 h-4 text-emerald-600" /> Monthly Request Trends
                        </h3>
                        <div class="h-[300px]">
                            <Line :data="lineChartData" :options="chartOptions" />
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <Activity class="w-4 h-4 text-blue-600" /> Request Status Breakdown
                        </h3>
                        <div class="h-[300px]">
                            <Bar :data="statusChartData" :options="chartOptions" />
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <Trophy class="w-4 h-4 text-emerald-500" /> Top Requested Ordinances
                        </h3>
                        <div class="h-[280px]">
                            <Bar :data="topOrdinancesData" :options="horizontalOptions" />
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <Trophy class="w-4 h-4 text-blue-500" /> Top Requested Resolutions
                        </h3>
                        <div class="h-[280px]">
                            <Bar :data="topResolutionsData" :options="horizontalOptions" />
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>
</template>

    <style scoped>
    /* Smooth Chart transitions */
    canvas {
        transition: all 0.3s ease;
    }
    </style>