<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import TextLink from '@/components/TextLink.vue';
import { MailCheck, LogOut } from 'lucide-vue-next';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <Head title="Email Verification" />

    <div class="min-h-svh w-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-8 text-black"
        style="background-image: url('/images/lg.jpg')">
        
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div class="absolute top-0 right-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-yellow-400 opacity-20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-green-900 opacity-20 blur-3xl pointer-events-none"></div>

        <div class="relative w-full sm:max-w-[450px] md:max-w-[500px] backdrop-blur-xl bg-white/85 text-black border border-white/40 shadow-2xl rounded-[2.5rem] overflow-hidden transition-all duration-300">
            
            <div class="h-2 bg-[#FFCA52] w-full"></div>

            <div class="px-6 py-10 md:px-12 md:py-14 text-center">
                
                <div class="flex justify-center mb-6">
                    <div class="p-4 bg-green-100 rounded-full border-4 border-white shadow-md">
                        <MailCheck class="w-10 h-10 md:w-12 md:h-12 text-green-800" />
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">
                        Verify Your
                        <span class="block text-green-800">Email Address</span>
                    </h1>
                    <p class="text-sm md:text-base text-gray-600 mt-4 font-medium leading-relaxed">
                        Thanks for signing up! Please verify your email by clicking the link we just sent to your inbox.
                    </p>
                </div>

                <div v-if="status === 'verification-link-sent'"
                    class="mb-8 p-4 text-xs md:text-sm text-green-700 bg-green-50/80 rounded-xl border border-green-200 backdrop-blur-sm font-bold">
                    A new verification link has been sent to your email address.
                </div>

                <Form 
                    v-bind="send.form()" 
                    v-slot="{ processing }"
                    class="flex flex-col gap-4"
                >
                    <Button type="submit"
                        class="w-full h-12 md:h-14 text-sm md:text-base font-black tracking-widest
                        bg-green-900 text-white hover:bg-[#FFCA52] hover:text-green-950 
                        rounded-xl transition-all duration-300 shadow-xl shadow-green-900/20 active:scale-[0.98]"
                        :disabled="processing">

                        <Spinner v-if="processing" />
                        <span v-else class="uppercase">Resend Verification Email</span>
                    </Button>

                    <div class="mt-4">
                        <TextLink 
                            :href="logout()" 
                            as="button"
                            class="inline-flex items-center gap-2 text-xs md:text-sm text-gray-500 font-bold hover:text-red-600 transition-colors uppercase tracking-wider"
                        >
                            <LogOut class="w-4 h-4" />
                            Log Out
                        </TextLink>
                    </div>
                </Form>
            </div>

            <div class="bg-gray-50/50 py-4 px-8 border-t border-white/30 text-center">
                <p class="text-[8px] md:text-[10px] text-gray-500 font-bold tracking-[0.2em] uppercase">
                    Legislative Portal • Security Check
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.v-enter-active, .v-leave-active {
  transition: all 0.3s ease;
}
.v-enter-from, .v-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>