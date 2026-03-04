<script setup lang="ts">
import { ref } from 'vue';
import { Form, Head } from "@inertiajs/vue3";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { Spinner } from "@/components/ui/spinner";
import InputError from "@/components/InputError.vue";
import { Eye, EyeOff, Lock, Mail } from 'lucide-vue-next'; 
import { update } from '@/routes/password';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);

// Password visibility toggles
const passwordType = ref('password');
const confirmPasswordType = ref('password');

const togglePasswordVisibility = () => {
    passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
};

const toggleConfirmVisibility = () => {
    confirmPasswordType.value = confirmPasswordType.value === 'password' ? 'text' : 'password';
};
</script>

<template>
    <Head title="Reset Password" />

    <div class="min-h-svh w-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-8 text-black"
        style="background-image: url('/images/lg.jpg')">
        
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div class="absolute top-0 right-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-yellow-400 opacity-20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-green-900 opacity-20 blur-3xl pointer-events-none"></div>

        <div class="relative w-full sm:max-w-[450px] md:max-w-[500px] backdrop-blur-xl bg-white/85 text-black border border-white/40 shadow-2xl rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden transition-all duration-300">
            
            <div class="h-1.5 md:h-2 bg-[#FFCA52] w-full"></div>

            <div class="px-6 py-8 md:px-12 md:py-14 text-center">
                
                <div class="flex justify-center mb-4 md:mb-6">
                    <img src="/images/lg.jpg"
                        class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full border-4 border-white shadow-lg"
                        alt="Municipality Logo" />
                </div>

                <div class="mb-6 md:mb-8">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">
                        Secure Reset
                        <span class="block text-green-800">New Password</span>
                    </h1>
                    <p class="text-sm md:text-base text-gray-600 mt-2 font-medium">
                        Update your legislative portal credentials
                    </p>
                </div>

                <Form 
                    v-bind="update.form()" 
                    :transform="(data) => ({ ...data, token, email })"
                    :reset-on-success="['password', 'password_confirmation']"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-4 md:gap-5 text-left"
                >

                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">Email Address</Label>
                        <div class="relative group opacity-70">
                            <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-700" />
                            <Input
                                type="email"
                                v-model="inputEmail"
                                readonly
                                class="bg-gray-100 text-black border-gray-200 h-11 md:h-13 pl-11 md:pl-12 rounded-xl text-sm md:text-base"
                            />
                        </div>
                        <InputError :message="errors.email" />
                    </div>

                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">New Password</Label>
                        <div class="relative group">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-700 group-focus-within:text-green-800 transition-colors" />
                            <Input 
                                :type="passwordType" 
                                name="password" 
                                required 
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

                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">Confirm New Password</Label>
                        <div class="relative group">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-700 group-focus-within:text-green-800 transition-colors" />
                            <Input 
                                :type="confirmPasswordType" 
                                name="password_confirmation" 
                                required 
                                placeholder="••••••••"
                                class="bg-white text-black border-gray-200 h-11 md:h-13 pl-11 md:pl-12 rounded-xl focus:ring-2 focus:ring-green-800 focus:bg-white transition-all text-sm md:text-base placeholder:text-gray-700"
                            />
                            <button type="button" @click="toggleConfirmVisibility"
                                class="absolute right-0 top-0 h-full w-10 md:w-12 flex items-center justify-center text-gray-700 hover:text-green-800 transition-colors">
                                <Eye v-if="confirmPasswordType === 'password'" class="w-4 h-4 md:w-5 md:h-5" />
                                <EyeOff v-else class="w-4 h-4 md:w-5 md:h-5" />
                            </button>
                        </div>
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <Button type="submit"
                        class="w-full h-12 md:h-14 mt-4 text-sm md:text-base font-black tracking-widest
                        bg-green-900 text-white hover:bg-[#FFCA52] hover:text-green-950 
                        rounded-xl transition-all duration-300 shadow-xl shadow-green-900/20 active:scale-[0.98]"
                        :disabled="processing">

                        <Spinner v-if="processing" />
                        <span v-else class="uppercase">Update Password</span>
                    </Button>

                </Form>
            </div>

            <div class="bg-gray-50/50 py-3 md:py-4 px-6 md:px-8 border-t border-white/30 text-center">
                <p class="text-[8px] md:text-[10px] text-gray-500 font-bold tracking-[0.15em] md:tracking-[0.2em] uppercase">
                    Account Security Management • Concepcion
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Matching the fade transitions from your login */
.v-enter-active, .v-leave-active {
  transition: all 0.2s ease;
}
.v-enter-from, .v-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}
</style>