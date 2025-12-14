<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    FileText,
    LayoutGrid,
    LogOut,
    Megaphone,
    Settings,
    User,
    Users,
    ChevronDown,
    ChevronUp,
    Library,
    FileSearch,
    ClipboardList
} from 'lucide-vue-next';
import { computed, ref, onMounted } from 'vue';
import AppLogo from './AppLogo.vue';

const { props } = usePage();
const authUser = computed(() => props.auth?.user);

interface NavItem {
    title: string;
    href: string;
    icon: any;
}

const mainNavItems: NavItem[] = [
  { title: 'Dashboard', href: '/', icon: LayoutGrid },

  // More appropriate icon for ordinances
  { title: 'Ordinances', href: '/admin-ordinances', icon: Library },

  // Legal document icon for resolutions
  { title: 'Resolutions', href: '/admin-resolutions', icon: FileText },

  // Document with magnifying glass = request or review
  { title: 'Ordinance Request', href: '/ordinance-request', icon: FileSearch },

  // Clipboard list fits "requests"
  { title: 'Resolution Request', href: '/resolution-request', icon: ClipboardList },

  // Members stays the same
  { title: 'SB Members', href: '/officials', icon: Users },

  // Announcements stays same
  { title: 'Announcements', href: '/announcements', icon: Megaphone },
];

const currentRoute = computed(() => window.location.pathname);
const isActive = (href: string) => {
    if (href === '/') return currentRoute.value === '/';
    return currentRoute.value.startsWith(href) &&
        (currentRoute.value.length === href.length ||
            currentRoute.value.charAt(href.length) === '/');
};

// Profile dropdown
const isProfileDropdownOpen = ref(false);
const toggleProfile = () => (isProfileDropdownOpen.value = !isProfileDropdownOpen.value);

// click outside dropdown
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

onMounted(() => {
    window.addEventListener('click', handleClickOutside);
});
</script>

<template>
    <aside
        class="relative flex h-screen w-64 flex-col bg-white/30 backdrop-blur-xl text-black shadow-2xl border-r border-white/20"
    >
        <!-- Header -->
        <div class="flex items-center gap-3 border-b border-white/20 px-6 py-5">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-lg bg-black/70 text-white shadow-lg"
            >
                <AppLogo class="h-6 w-6" />
            </div>
            <div>
                <p class="text-lg font-bold tracking-tight">Concepcion LGU</p>
                <p class="text-xs font-medium text-black/60">Admin Portal</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-6 flex-1 overflow-y-auto custom-scrollbar">
            <ul class="flex flex-col gap-1 px-4">
                <li v-for="item in mainNavItems" :key="item.title">
                    <Link
                        :href="item.href"
                        class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200"
                        :class="{
                            'bg-white/30 backdrop-blur-xl shadow-inner text-black font-semibold border-l-4 border-emerald-500':
                                isActive(item.href),
                            'text-black/70 hover:bg-white/40 hover:text-black hover:shadow-md':
                                !isActive(item.href),
                        }"
                    >
                        <component
                            :is="item.icon"
                            class="h-5 w-5 transition-transform"
                            :class="{
                                'text-black': isActive(item.href),
                                'group-hover:text-black': !isActive(item.href),
                            }"
                        />
                        <span class="text-sm font-medium">{{ item.title }}</span>

                        <!-- Active indicator bar -->
                        <div
                            v-if="isActive(item.href)"
                            class="absolute top-0 bottom-0 right-0 w-1 bg-emerald-600 rounded-r-lg"
                        ></div>
                    </Link>
                </li>
            </ul>
        </nav>

        <div class="px-4 py-3">
            <div class="h-px bg-white/20"></div>
        </div>

        <!-- Profile -->
        <div class="relative px-4 pb-4">
            <div
                ref="userProfileButton"
                class="flex items-center justify-between cursor-pointer rounded-xl border border-white/30 bg-white/20 backdrop-blur-xl p-3 transition-colors hover:bg-white/40"
                @click="toggleProfile"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-black text-white font-bold shadow-md"
                    >
                        {{ authUser?.name ? authUser.name.charAt(0).toUpperCase() : 'A' }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-black">
                            {{ authUser?.name || 'Administrator' }}
                        </p>
                        <p class="truncate text-xs text-black/50">Municipal Portal</p>
                    </div>
                </div>
                <component
                    :is="isProfileDropdownOpen ? ChevronUp : ChevronDown"
                    class="w-4 h-4 text-black/60"
                />
            </div>

            <!-- Profile Dropdown -->
            <div
                v-if="isProfileDropdownOpen"
                ref="profileDropdown"
                class="animate-fadeIn absolute right-4 bottom-[90px] left-4 z-50 overflow-hidden rounded-xl bg-white/70 backdrop-blur-xl border border-black/10 shadow-xl text-black"
            >
                <Link
                    href="/profile"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium hover:bg-black/10 transition"
                >
                    <User class="h-4 w-4 text-black/60" />
                    My Profile
                </Link>

                <Link
                    href="/settings"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium hover:bg-black/10 transition"
                >
                    <Settings class="h-4 w-4 text-black/60" />
                    Settings
                </Link>

                <div class="h-px bg-black/10"></div>

                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex w-full items-center gap-3 px-4 py-3 text-sm font-medium hover:bg-red-100 text-red-600 transition"
                >
                    <LogOut class="h-4 w-4 text-red-500" />
                    Logout
                </Link>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.25);
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.45);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fadeIn {
    animation: fadeIn 0.18s ease-out;
}
</style>
