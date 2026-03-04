<script setup lang="ts">
import { ref } from 'vue';
import { Form, Head } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/InputError.vue';
import { ShieldCheck, Lock, Eye, EyeOff } from 'lucide-vue-next';
import { store } from '@/routes/password/confirm';

const passwordType = ref('password');
const togglePasswordVisibility = () => {
    passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
};
</script>

<template>
    <Head title="Confirm Password" />

    <div class="min-h-svh w-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-8 text-black"
        style="background-image: url('/images/lg.jpg')">
        
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div class="absolute top-0 right-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-yellow-400 opacity-20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-green-900 opacity-20 blur-3xl pointer-events-none"></div>

        <div class="relative w-full sm:max-w-[450px] md:max-w-[500px] backdrop-blur-xl bg-white/85 text-black border border-white/40 shadow-2xl rounded-[2.5rem] overflow-hidden transition-all duration-300">
            
            <div class="h-2 bg-[#FFCA52] w-full"></div>

            <div class="px-6 py-10 md:px-12 md:py-14 text-center">
                
                <div class="flex justify-center mb-6">
                    <div class="p-4 bg-indigo-50 rounded-full border-4 border-white shadow-md">
                        <ShieldCheck class="w-10 h-10 md:w-12 md:h-12 text-indigo-700" />
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">
                        Security
                        <span class="block text-green-800">Confirmation</span>
                    </h1>
                    <p class="text-sm md:text-base text-gray-600 mt-4 font-medium leading-relaxed">
                        This is a secure area. Please confirm your password to continue to the legislative portal.
                    </p>
                </div>

                <Form 
                    v-bind="store.form()" 
                    reset-on-success
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-5 text-left"
                >

                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">
                            Current Password
                        </Label>

                        <div class="relative group">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-700 group-focus-within:text-green-800 transition-colors" />
                            
                            <Input 
                                :type="passwordType" 
                                id="password"
                                name="password" 
                                required 
                                autocomplete="current-password"
                                autofocus
                                placeholder="••••••••"
                                class="bg-white text-black border-gray-200 h-11 md:h-13 pl-11 md:pl-12 rounded-xl focus:ring-2 focus:ring-green-800 focus:bg-white transition-all text-sm md:text-base placeholder:text-gray-700"
                            />

                            <button type="button" @click="togglePasswordVisibility"
                                class="absolute right-0 top-0 h-full w-10 md:w-12 flex items-center justify-center text-gray-700 hover:text-green-800 transition-colors">
                                <Eye v-if="passwordType === 'password'" class="w-4 h-4 md:w-5 md:h-5" />
                                <EyeOff v-else class="w-4 h-4 md:w-5 md:h-5" />
                            </button>
                        </div>

                        <InputError :message="errors.password" />
                    </div>

                    <Button type="submit"
                        class="w-full h-12 md:h-14 mt-2 text-sm md:text-base font-black tracking-widest
                        bg-green-900 text-white hover:bg-[#FFCA52] hover:text-green-950 
                        rounded-xl transition-all duration-300 shadow-xl shadow-green-900/20 active:scale-[0.98]"
                        :disabled="processing">

                        <Spinner v-if="processing" />
                        <span v-else class="uppercase">Confirm Access</span>
                    </Button>

                </Form>
            </div>

            <div class="bg-gray-50/50 py-4 px-8 border-t border-white/30 text-center">
                <p class="text-[8px] md:text-[10px] text-gray-500 font-bold tracking-[0.2em] uppercase">
                    Authorized Personnel Only • Concepcion
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.v-enter-active, .v-leave-active {
  transition: all 0.2s ease;
}
.v-enter-from, .v-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}
</style>