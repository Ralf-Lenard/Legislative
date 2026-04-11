<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    BookOpen,
    ChevronUp,
    ChevronLeft,
    ClipboardList,
    FileSearch,
    FileText,
    LayoutGrid,
    Library,
    LogOut,
    Megaphone,
    User,
    Users,
    Settings,
    BookIcon,
    HelpingHand,
    Network,
    MessageSquare
} from 'lucide-vue-next';
import { computed, onMounted, ref, onUnmounted } from 'vue';
import AppLogo from './AppLogo.vue';
import NotificationDropdown from '@/components/NotificationDropdown.vue'

const { props } = usePage();
const authUser = computed(() => props.auth?.user);

const pendingOrdinances = computed(() => props.ordinanceRequestStatus?.pending || 0);
const pendingResolutions = computed(() => props.resolutionRequestStatus?.pending || 0);

// Sidebar collapse persistence
const isCollapsed = ref(localStorage.getItem('sidebar-collapsed') === 'true');

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
    localStorage.setItem('sidebar-collapsed', isCollapsed.value.toString());
};

const navGroups = computed(() => {

    const managementItems: Array<any> = [
        ...(authUser.value?.usertype === 'super_admin'
            ? [{ title: 'Manage Users', href: '/super-admin-users', icon: Users }]
            : []),

        { title: 'Ordinances', href: '/admin-ordinances', icon: Library },
        { title: 'Resolutions', href: '/admin-resolutions', icon: FileText },
        { title: 'Assistance', href: '/admin-assistances', icon: HelpingHand },
        { title: 'Organizational Chart', href: '/admin-organizational-chart', icon: Network },
        { title: 'Sessions', href: '/admin-sessions', icon: BookOpen },
        { title: 'Library', href: '/admin-library', icon: BookIcon },
    ];

    const publicRequestItems: Array<any> = [
        ...(authUser.value?.usertype === 'super_admin'
            ? [
                {
                    title: 'Ordinance Requests',
                    href: '/ordinance-request',
                    icon: FileSearch,
                    count: pendingOrdinances
                },
                {
                    title: 'Resolution Requests',
                    href: '/resolution-request',
                    icon: ClipboardList,
                    count: pendingResolutions
                },
                {
                    title: 'Feedback',
                    href: '/admin/feedback',
                    icon: MessageSquare
                }
            ]
            : authUser.value?.usertype === 'admin'
            ? [
                {
                    title: 'Feedback',
                    href: '/admin/feedback',
                    icon: MessageSquare
                }
            ]
            : [])
    ];

    return [
        {
            label: 'System Overview',
            items: [
                { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid }
            ],
        },
        {
            label: 'Legislative Management',
            items: managementItems,
        },

        ...(publicRequestItems.length
            ? [{
                label: 'Public Requests',
                items: publicRequestItems
            }]
            : []),

        {
            label: 'Home Content',
            items: [
                { title: 'Home Content', href: '/home-content', icon: Megaphone }
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
    if (
        isProfileDropdownOpen.value &&
        profileDropdown.value &&
        !profileDropdown.value.contains(event.target as Node) &&
        userProfileButton.value &&
        !userProfileButton.value.contains(event.target as Node)
    ) {
        isProfileDropdownOpen.value = false;
    }
};

onMounted(() => window.addEventListener('click', handleClickOutside));
onUnmounted(() => window.removeEventListener('click', handleClickOutside));
</script>

<template>
    <aside 
        class="relative z-50 flex h-screen flex-col border-r border-slate-200 bg-white/80 backdrop-blur-xl transition-all duration-300 ease-in-out"
        :class="isCollapsed ? 'w-20' : 'w-72'"
    >
        <button 
            @click="toggleSidebar" 
            class="absolute -right-3 top-10 z-[70] flex h-6 w-6 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:text-emerald-600 hover:scale-110 active:scale-95"
            :class="{'rotate-180': isCollapsed}"
        >
            <ChevronLeft class="h-4 w-4" />
        </button>
        
        <div class="px-4 py-8 shrink-0">
            <div class="flex items-center gap-2" :class="isCollapsed ? 'flex-col justify-center' : 'justify-between px-2'">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-200/50 ring-4 ring-emerald-50">
                        <AppLogo class="h-6 w-6" />
                    </div>
                    <div v-if="!isCollapsed" class="min-w-0">
                        <h2 class="truncate text-sm font-black uppercase tracking-tight text-slate-800">Concepcion</h2>
                    </div>
                </div>

                <div :class="isCollapsed ? 'mt-4' : ''">
                    <NotificationDropdown :user="authUser" />
                </div>
            </div>
        </div>

        <nav class="custom-scrollbar flex-1 overflow-y-auto overflow-x-visible px-4 pb-4">
            <div v-for="(group, idx) in navGroups" :key="idx" class="mb-8">
                <h3 v-if="!isCollapsed" class="mb-3 px-4 text-[10px] font-black uppercase tracking-[0.15em] text-slate-400/80">
                    {{ group.label }}
                </h3>
                <div v-else class="mb-3 flex justify-center">
                    <div class="h-px w-6 bg-slate-200"></div>
                </div>
                
                <ul class="space-y-1.5">
                    <li v-for="item in group.items" :key="item.title" class="group relative flex items-center">
                        <Link
                            :href="item.href"
                            class="relative flex flex-1 items-center rounded-xl px-4 py-3 transition-all duration-200"
                            :class="[
                                isActive(item.href)
                                    ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200 ring-1 ring-emerald-500'
                                    : 'text-slate-500 hover:bg-emerald-50/50 hover:text-emerald-700',
                                isCollapsed ? 'justify-center' : 'justify-between'
                            ]"
                        >
                            <div class="flex items-center gap-3.5">
                                <component 
                                    :is="item.icon" 
                                    class="h-5 w-5 shrink-0 transition-all duration-300"
                                    :class="isActive(item.href) ? 'scale-110' : 'group-hover:scale-110 group-hover:text-emerald-600'"
                                />
                                <span v-if="!isCollapsed" class="text-sm font-bold tracking-tight whitespace-nowrap">{{ item.title }}</span>
                            </div>
                        </Link>

                        <div 
                            v-if="isCollapsed" 
                            class="pointer-events-none invisible fixed left-[75px] z-[9999] ml-2 flex items-center opacity-0 scale-90 -translate-x-2 transition-all duration-200 group-hover:visible group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0"
                        >
                            <div class="relative flex items-center rounded-lg bg-emerald-600 px-3 py-2 text-[11px] font-bold text-white shadow-xl ring-1 ring-emerald-400/50">
                                <div class="absolute -left-1 top-1/2 h-2.5 w-2.5 -translate-y-1/2 rotate-45 bg-emerald-600"></div>
                                <span class="whitespace-nowrap">{{ item.title }}</span>
                                <span v-if="item.count && item.count > 0" class="ml-2 rounded-md bg-white px-1.5 py-0.5 text-[9px] font-black text-emerald-600">
                                    {{ item.count }}
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="relative p-4 mt-auto shrink-0 overflow-visible z-[60]">
            <transition name="slide-up">
                <div v-if="isProfileDropdownOpen" ref="profileDropdown" 
                    class="absolute bottom-[105%] z-[100] overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl ring-1 ring-black/5"
                    :class="isCollapsed ? 'left-4 w-56' : 'left-4 right-4'"
                >
                    <div class="px-3 py-2 border-b border-slate-50 mb-1">
                         <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Account Menu</p>
                    </div>
                    <Link href="/profile-settings" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-600">
                        <Settings class="h-4 w-4" /> Profile Settings
                    </Link>
                    <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-black text-red-500 transition hover:bg-red-50">
                        <LogOut class="h-4 w-4" /> Sign Out
                    </Link>
                </div>
            </transition>

            <button 
                ref="userProfileButton" 
                @click="toggleProfile"
                class="group relative flex w-full items-center gap-3 rounded-2xl border border-slate-100 bg-white p-2.5 shadow-sm transition-all hover:border-emerald-200 hover:shadow-md active:scale-[0.98]"
                :class="isCollapsed ? 'justify-center' : ''"
            >
                <div class="relative h-10 w-10 shrink-0 overflow-hidden rounded-xl bg-slate-100 shadow-inner">
                    <img v-if="authUser?.profile_photo" :src="`/storage/${authUser.profile_photo.replace('/storage/', '')}`" class="h-full w-full object-cover"/>
                    <div v-else class="flex h-full w-full items-center justify-center bg-emerald-600 font-black text-white text-sm">
                        {{ authUser?.name?.charAt(0) }}
                    </div>
                </div>
                
                <div v-if="!isCollapsed" class="min-w-0 flex-1 text-left flex items-center justify-between">
                    <p class="truncate text-sm font-bold text-slate-800 tracking-tight capitalize">
                        {{ authUser?.name || 'Admin' }}
                    </p>
                    <ChevronUp class="h-4 w-4 text-slate-400 transition-transform duration-300" :class="isProfileDropdownOpen ? 'rotate-180' : ''" />
                </div>

                <div v-if="isCollapsed && !isProfileDropdownOpen" class="pointer-events-none invisible fixed left-[75px] z-[9999] ml-2 flex items-center opacity-0 scale-90 -translate-x-2 transition-all duration-200 group-hover:visible group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0">
                    <div class="relative rounded-lg bg-emerald-600 px-3 py-2 text-[11px] font-bold text-white shadow-xl ring-1 ring-emerald-400/50">
                        <div class="absolute -left-1 top-1/2 h-2.5 w-2.5 -translate-y-1/2 rotate-45 bg-emerald-600"></div>
                        <span class="whitespace-nowrap">{{ authUser?.name || 'Admin' }}</span>
                    </div>
                </div>
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
/* Crucial for allowing the profile dropdown to pop OUT of the sidebar footer */
.mt-auto {
    overflow: visible !important;
}

.slide-up-enter-active, .slide-up-leave-active {
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.slide-up-enter-from, .slide-up-leave-to {
    opacity: 0;
    transform: translateY(20px) scale(0.9);
}
</style>
