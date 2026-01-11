<script setup lang="ts">
    import { Link, usePage } from '@inertiajs/vue3';
    import {
        BookOpen,
        ChevronDown,
        ChevronUp,
        ClipboardList,
        FileSearch,
        FileText,
        LayoutGrid,
        Library,
        LogOut,
        Megaphone,
        Settings,
        User,
        Users,
    } from 'lucide-vue-next';
    import { computed, onMounted, ref } from 'vue';
    import AppLogo from './AppLogo.vue';
    
    const { props } = usePage();
    const authUser = computed(() => props.auth?.user);
    
    const pendingOrdinances = computed(() => props.ordinanceRequestStatus?.pending || 0);
    const pendingResolutions = computed(() => props.resolutionRequestStatus?.pending || 0);
    
    const navGroups = computed(() => {
    const managementItems: Array<any> = [
        // Only show "Manage Users" if super admin
        ...(authUser.value?.usertype === 'super_admin'
            ? [{ title: 'Manage Users', href: '/super-admin-users', icon: Users }]
            : []),
        { title: 'Ordinances', href: '/admin-ordinances', icon: Library },
        { title: 'Resolutions', href: '/admin-resolutions', icon: FileText },
        { title: 'SB Members', href: '/admin-officials', icon: Users },
        { title: 'Sessions', href: '/admin-sessions', icon: BookOpen },
    ];

    return [
        {
            label: 'Core',
            items: [
                { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid },
            ],
        },
        {
            label: 'Management',
            items: managementItems,
        },
        {
            label: 'Requests',
            items: [
                { title: 'Ordinance Requests', href: '/ordinance-request', icon: FileSearch, count: pendingOrdinances },
                { title: 'Resolution Requests', href: '/resolution-request', icon: ClipboardList, count: pendingResolutions },
            ],
        },
        {
            label: 'Communication',
            items: [
                { title: 'Announcements', href: '/announcements', icon: Megaphone },
            ],
        },
    ];
});

    
    const currentRoute = computed(() => window.location.pathname);
    const isActive = (href: string) => {
        if (href === '/') return currentRoute.value === '/';
        return currentRoute.value.startsWith(href);
    };
    
    const isProfileDropdownOpen = ref(false);
    const toggleProfile = () => (isProfileDropdownOpen.value = !isProfileDropdownOpen.value);
    
    const profileDropdown = ref<HTMLElement | null>(null);
    const userProfileButton = ref<HTMLElement | null>(null);
    
    const handleClickOutside = (event: MouseEvent) => {
        if (isProfileDropdownOpen.value && profileDropdown.value && !profileDropdown.value.contains(event.target as Node) && userProfileButton.value && !userProfileButton.value.contains(event.target as Node)) {
            isProfileDropdownOpen.value = false;
        }
    };
    
    onMounted(() => {
        window.addEventListener('click', handleClickOutside);
    });
    </script>
    
    <template>
    <aside class="relative flex h-screen w-64 flex-col border-r border-slate-200 bg-slate-50/80 text-slate-900 backdrop-blur-xl transition-all duration-300">
        <!-- Sidebar Header -->
        <div class="flex items-center gap-3 px-6 py-8">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-200">
                <AppLogo class="h-6 w-6" />
            </div>
            <div class="min-w-0 overflow-hidden">
                <h2 class="truncate text-sm font-bold uppercase tracking-wider text-slate-800">Concepcion LGU</h2>
                <div class="flex items-center gap-1.5">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-500">ADMIN PORTAL</span>
                </div>
            </div>
        </div>
    
        <!-- Navigation -->
        <nav class="custom-scrollbar flex-1 overflow-y-auto px-4 pb-4">
            <div v-for="(group, idx) in navGroups" :key="idx" class="mb-6">
                <h3 class="mb-2 px-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ group.label }}</h3>
                <ul class="space-y-1">
                    <li v-for="item in group.items" :key="item.title">
                        <Link
                            :href="item.href"
                            class="group flex items-center justify-between rounded-xl px-4 py-2.5 transition-all duration-200"
                            :class="isActive(item.href)
                                ? 'bg-emerald-600 text-white shadow-md shadow-emerald-100 ring-1 ring-emerald-600/10'
                                : 'text-slate-600 hover:bg-white hover:text-emerald-600 hover:shadow-sm'">
                            <div class="flex items-center gap-3">
                                <component :is="item.icon" class="h-5 w-5 transition-transform duration-300 group-hover:scale-110"/>
                                <span class="text-sm font-semibold tracking-tight">{{ item.title }}</span>
                            </div>
                            <span v-if="item.count && item.count > 0" class="flex h-5 min-w-[20px] items-center justify-center rounded-full bg-amber-400 px-1 text-[10px] font-black text-amber-950 shadow-sm">
                                {{ item.count }}
                            </span>
                        </Link>
                    </li>
                </ul>
            </div>
        </nav>
    
        <!-- Profile -->
        <div class="relative mt-auto border-t border-slate-200 p-4">
            <transition name="slide-up">
                <div v-if="isProfileDropdownOpen" ref="profileDropdown" class="absolute bottom-20 left-4 right-4 z-50 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-2xl">
                    <Link href="/admin/profile" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        <User class="h-4 w-4 text-slate-400" />
                        Account Settings
                    </Link>
                    <div class="my-1 h-px bg-slate-100"></div>
                    <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-3 rounded-lg px-4 py-2 text-sm font-bold text-red-500 transition hover:bg-red-50">
                        <LogOut class="h-4 w-4" />
                        Sign Out
                    </Link>
                </div>
            </transition>
    
            <button ref="userProfileButton" class="group flex w-full items-center gap-3 rounded-2xl border border-slate-200 bg-white p-2.5 shadow-sm transition-all hover:border-emerald-200 hover:bg-emerald-50/50" @click="toggleProfile">
                <div class="relative h-10 w-10 shrink-0 overflow-hidden rounded-xl border-2 border-white bg-slate-100 shadow-sm transition-transform group-hover:scale-105">
                    <img v-if="authUser?.profile_photo" :src="`/storage/${authUser.profile_photo.replace('/storage/', '')}`" class="h-full w-full object-cover"/>
                    <div v-else class="flex h-full w-full items-center justify-center bg-emerald-600 font-bold text-white">
                        {{ authUser?.name?.charAt(0).toUpperCase() || 'A' }}
                    </div>
                </div>
                <div class="min-w-0 flex-1 text-left">
                    <p class="truncate text-sm font-bold text-slate-800">{{ authUser?.name || 'Admin' }}</p>
                    <p class="truncate text-[10px] font-medium text-slate-500">Legislative Administrator</p>
                </div>
                <ChevronUp class="h-4 w-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': isProfileDropdownOpen}" />
            </button>
        </div>
    </aside>
    </template>
    
    <style scoped>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        border-radius: 20px;
    }
    
    /* Transitions */
    .slide-up-enter-active, .slide-up-leave-active {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .slide-up-enter-from, .slide-up-leave-to {
        opacity: 0;
        transform: translateY(10px) scale(0.95);
    }
    </style>
    