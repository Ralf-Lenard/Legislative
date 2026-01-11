<script setup lang="ts">
    import { Link, usePage } from '@inertiajs/vue3';
    import {
        BookOpen,
        ChevronUp,
        ClipboardList,
        FileSearch,
        FileText,
        LayoutGrid,
        Library,
        LogOut,
        Megaphone,
        User,
        Users,
        Bell,
        Settings,
    } from 'lucide-vue-next';
    import { computed, onMounted, ref, onUnmounted } from 'vue';
    import AppLogo from './AppLogo.vue';

    import NotificationDropdown from '@/components/NotificationDropdown.vue'
    
    const { props } = usePage();
    const authUser = computed(() => props.auth?.user);
    
    const pendingOrdinances = computed(() => props.ordinanceRequestStatus?.pending || 0);
    const pendingResolutions = computed(() => props.resolutionRequestStatus?.pending || 0);
    
    const navGroups = computed(() => {
        const managementItems: Array<any> = [
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
                label: 'System Overview',
                items: [{ title: 'Dashboard', href: '/dashboard', icon: LayoutGrid }],
            },
            {
                label: 'Legislative Management',
                items: managementItems,
            },
            {
                label: 'Public Requests',
                items: [
                    { title: 'Ordinance Requests', href: '/ordinance-request', icon: FileSearch, count: pendingOrdinances },
                    { title: 'Resolution Requests', href: '/resolution-request', icon: ClipboardList, count: pendingResolutions },
                ],
            },
            {
                label: 'Home Content',
                items: [{ title: 'Home Content', href: '/home-content', icon: Megaphone }],
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
        if (isProfileDropdownOpen.value && 
            profileDropdown.value && !profileDropdown.value.contains(event.target as Node) && 
            userProfileButton.value && !userProfileButton.value.contains(event.target as Node)) {
            isProfileDropdownOpen.value = false;
        }
    };
    
    onMounted(() => window.addEventListener('click', handleClickOutside));
    onUnmounted(() => window.removeEventListener('click', handleClickOutside));
    </script>
    
    <template>
        <aside class="relative flex h-screen w-72 flex-col border-r border-slate-200 bg-white/80 backdrop-blur-xl transition-all duration-300">
            
            <div class="px-6 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-200/50 ring-4 ring-emerald-50">
                            <AppLogo class="h-6 w-6" />
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate text-sm font-black uppercase tracking-tight text-slate-800">Concepcion</h2>
                            <div class="flex items-center gap-1.5">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin Portal</span>
                            </div>
                        </div>
                    </div>
    
                    <NotificationDropdown :user="authUser" />
                </div>
            </div>
    
            <nav class="custom-scrollbar flex-1 overflow-y-auto px-4 pb-4">
                <div v-for="(group, idx) in navGroups" :key="idx" class="mb-8">
                    <h3 class="mb-3 px-4 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400/80">
                        {{ group.label }}
                    </h3>
                    <ul class="space-y-1.5">
                        <li v-for="item in group.items" :key="item.title">
                            <Link
                                :href="item.href"
                                class="group relative flex items-center justify-between rounded-xl px-4 py-3 transition-all duration-200"
                                :class="isActive(item.href)
                                    ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200 ring-1 ring-emerald-500'
                                    : 'text-slate-500 hover:bg-emerald-50/50 hover:text-emerald-700'"
                            >
                                <div v-if="isActive(item.href)" class="absolute -left-1 h-6 w-1 rounded-r-full bg-white"></div>
                                
                                <div class="flex items-center gap-3.5">
                                    <component 
                                        :is="item.icon" 
                                        class="h-5 w-5 transition-all duration-300"
                                        :class="isActive(item.href) ? 'scale-110' : 'group-hover:scale-110 group-hover:text-emerald-600'"
                                    />
                                    <span class="text-sm font-bold tracking-tight">{{ item.title }}</span>
                                </div>
    
                                <span 
                                    v-if="item.count && item.count > 0" 
                                    class="flex h-5 min-w-[20px] items-center justify-center rounded-lg px-1.5 text-[10px] font-black shadow-sm transition-colors"
                                    :class="isActive(item.href) ? 'bg-emerald-400 text-emerald-950' : 'bg-amber-100 text-amber-600'"
                                >
                                    {{ item.count }}
                                </span>
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
    
            <div class="relative p-4 mt-auto">
                <transition name="slide-up">
                    <div v-if="isProfileDropdownOpen" ref="profileDropdown" class="absolute bottom-24 left-4 right-4 z-50 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl ring-1 ring-black/5">
                        <div class="px-4 py-3 mb-1 border-b border-slate-50">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Account</p>
                        </div>
                        <Link href="/profile-settings" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50 hover:text-emerald-600">
                            <Settings class="h-4 w-4" /> Profile Settings
                        </Link>
                        <div class="my-1 h-px bg-slate-100"></div>
                        <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-black text-red-500 transition hover:bg-red-50">
                            <LogOut class="h-4 w-4" /> Sign Out
                        </Link>
                    </div>
                </transition>
    
                <button 
                    ref="userProfileButton" 
                    @click="toggleProfile"
                    class="group flex w-full items-center gap-3 rounded-2xl border border-slate-100 bg-white p-2.5 shadow-sm transition-all hover:border-emerald-200 hover:shadow-md active:scale-[0.98]"
                >
                    <div class="relative h-10 w-10 shrink-0 overflow-hidden rounded-xl bg-slate-100 shadow-inner">
                        <img v-if="authUser?.profile_photo" :src="`/storage/${authUser.profile_photo.replace('/storage/', '')}`" class="h-full w-full object-cover"/>
                        <div v-else class="flex h-full w-full items-center justify-center bg-emerald-600 font-black text-white text-sm">
                            {{ authUser?.name?.charAt(0) }}
                        </div>
                    </div>
                    <div class="min-w-0 flex-1 text-left">
                        <p class="truncate text-[11px] font-black text-slate-400 uppercase tracking-wider leading-none mb-1">Administrator</p>
                        <p class="truncate text-sm font-bold text-slate-800 tracking-tight capitalize">
                            {{ authUser?.name || 'Admin User' }}
                        </p>
                    </div>
                    <ChevronUp class="h-4 w-4 text-slate-300 transition-transform duration-500" :class="{'rotate-180': isProfileDropdownOpen}" />
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
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    
    .slide-up-enter-active, .slide-up-leave-active {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .slide-up-enter-from, .slide-up-leave-to {
        opacity: 0;
        transform: translateY(20px) scale(0.9);
    }
    </style>