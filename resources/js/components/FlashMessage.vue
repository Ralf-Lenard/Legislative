<script setup lang="ts">
    import { computed, ref, watch, nextTick, onMounted, onUnmounted } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import { AlertTriangle, CheckCircle, FileText, X, XCircle } from 'lucide-vue-next';
    
    const page = usePage();
    const showFlash = ref(false);
    let flashTimer: ReturnType<typeof setTimeout> | null = null;
    
    const flashMessage = computed(() => page.props.flash || {});
    
    const hasFlashMessage = computed(
        () =>
            !!(
                flashMessage.value.success ||
                flashMessage.value.error ||
                flashMessage.value.warning ||
                flashMessage.value.info
            )
    );
    
    onMounted(() => {
        if (hasFlashMessage.value) {
            showFlash.value = true;
            flashTimer = setTimeout(() => {
                showFlash.value = false;
            }, 5000);
        }
    });
    
    onUnmounted(() => {
        if (flashTimer) clearTimeout(flashTimer);
    });
    
    watch(
        () => page.props.flash,
        (newVal) => {
            const newContent =
                newVal?.success || newVal?.error || newVal?.warning || newVal?.info || null;
    
            if (newContent) {
                if (flashTimer) clearTimeout(flashTimer);
                showFlash.value = false;
    
                nextTick(() => {
                    showFlash.value = true;
                    flashTimer = setTimeout(() => {
                        showFlash.value = false;
                    }, 5000);
                });
            }
        },
        { deep: true, immediate: true }
    );
    
    const dismissFlash = () => {
        showFlash.value = false;
        if (flashTimer) {
            clearTimeout(flashTimer);
            flashTimer = null;
        }
    };
    </script>
    
    <template>
        <Teleport to="body">
            <Transition
                enter-active-class="transition-all duration-500 ease-out"
                enter-from-class="opacity-0 translate-y-3 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition-all duration-300 ease-in"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-3 scale-95"
            >
                <div
                    v-if="hasFlashMessage && showFlash"
                    class="fixed top-6 left-1/2 transform -translate-x-1/2 
                           md:left-auto md:right-6 md:translate-x-0 
                           z-[9999] w-[90%] sm:w-[85%] md:w-96 pointer-events-auto"
                >
                    <div
                        :class="{
                            'bg-theme-primary text-white': flashMessage.success,
                            'bg-red-600 text-white': flashMessage.error,
                            'bg-yellow-600 text-white': flashMessage.warning,
                            'bg-blue-600 text-white': flashMessage.info
                        }"
                        class="rounded-xl shadow-2xl px-5 sm:px-6 py-4 sm:py-5 border border-white/20 backdrop-blur-md"
                    >
                        <div class="flex gap-4 items-start">
                            
                            <!-- ICON -->
                            <component
                                :is="
                                    flashMessage.success
                                        ? CheckCircle
                                        : flashMessage.error
                                        ? XCircle
                                        : flashMessage.warning
                                        ? AlertTriangle
                                        : FileText
                                "
                                class="h-6 w-6 sm:h-7 sm:w-7 flex-shrink-0 opacity-90"
                            />
    
                            <!-- MESSAGE -->
                            <div class="flex-1">
                                <h3 class="font-semibold text-base sm:text-lg">
                                    {{
                                        flashMessage.success
                                            ? 'Success!'
                                            : flashMessage.error
                                            ? 'Something Went Wrong'
                                            : flashMessage.warning
                                            ? 'Warning'
                                            : 'Notice'
                                    }}
                                </h3>
    
                                <p class="mt-1 text-xs sm:text-sm opacity-90 break-words">
                                    {{
                                        flashMessage.success ||
                                        flashMessage.error ||
                                        flashMessage.warning ||
                                        flashMessage.info
                                    }}
                                </p>
    
                                <!-- PROGRESS BAR -->
                                <div class="mt-3 h-1 w-full bg-white/30 rounded-full overflow-hidden">
                                    <div class="animate-progress h-full bg-white/90"></div>
                                </div>
                            </div>
    
                            <!-- CLOSE BUTTON -->
                            <button
                                @click="dismissFlash"
                                class="text-white/60 hover:text-white transition p-1"
                            >
                                <X class="h-4 w-4 sm:h-5 sm:w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </template>
    
    <style scoped>
    .bg-theme-primary {
        background-color: #1b5e20 !important;
    }
    
    @keyframes progressSlide {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
    
    .animate-progress {
        animation: progressSlide 5s linear forwards;
    }
    </style>
    