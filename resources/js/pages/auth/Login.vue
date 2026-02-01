<script setup lang="ts">
    import { ref } from 'vue';
    import { Form, Head } from "@inertiajs/vue3";
    import { Input } from "@/components/ui/input";
    import { Label } from "@/components/ui/label";
    import { Button } from "@/components/ui/button";
    import { Checkbox } from "@/components/ui/checkbox";
    import { Spinner } from "@/components/ui/spinner";
    import TextLink from "@/components/TextLink.vue";
    import InputError from "@/components/InputError.vue";
    import { Eye, EyeOff, Lock, Mail } from 'lucide-vue-next'; 
        
    import { store } from "@/routes/login";
    import { register } from "@/routes";
    import { request } from "@/routes/password";
        
    defineProps<{
        status?: string;
        canResetPassword: boolean;
        canRegister: boolean;
    }>();
    
    const passwordType = ref('password');
    const togglePasswordVisibility = () => {
        passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
    };
</script>

<template>
    <Head title="Login" />

    <div class="min-h-svh w-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 py-8"
        style="background-image: url('/images/lg.jpg')">
        
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
        <div class="absolute top-0 right-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-yellow-400 opacity-20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 h-[200px] md:h-[400px] w-[200px] md:w-[400px] rounded-full bg-green-900 opacity-20 blur-3xl pointer-events-none"></div>

        <div class="relative w-full sm:max-w-[450px] md:max-w-[500px] backdrop-blur-xl bg-white/85 border border-white/40 shadow-2xl rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden transition-all duration-300">
            
            <div class="h-1.5 md:h-2 bg-[#FFCA52] w-full"></div>

            <div class="px-6 py-8 md:px-12 md:py-14 text-center">
                <div class="flex justify-center mb-4 md:mb-6">
                    <img src="/images/lg.jpg" class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-full border-4 border-white shadow-lg" alt="Municipality Logo" />
                </div>

                <div class="mb-6 md:mb-8">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">
                        Sangguniang Bayan
                        <span class="block text-green-800">Concepcion</span>
                    </h1>
                    <p class="text-sm md:text-base text-gray-600 mt-2 font-medium">Official Legislative Portal Access</p>
                </div>

                <div v-if="status" class="mb-6 p-3 text-xs md:text-sm text-green-700 bg-green-50/50 rounded-lg border border-green-300 backdrop-blur-sm">
                    {{ status }}
                </div>

                <Form v-bind="store.form()" :reset-on-success="['password']" v-slot="{ errors, processing }" class="flex flex-col gap-4 md:gap-5 text-left">
                    
                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">Email Address</Label>
                        <div class="relative group">
                            <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400 group-focus-within:text-green-800 transition-colors" />
                            <Input type="email" name="email" required autocomplete="email"
                                placeholder="example@gmail.com"
                                class="bg-white/60 border-gray-200 h-11 md:h-13 pl-11 md:pl-12 rounded-xl focus:ring-2 focus:ring-green-800 focus:bg-white transition-all text-sm md:text-base" />
                        </div>
                        <InputError :message="errors.email" />
                    </div>

                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">Password</Label>
                        <div class="relative group">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 md:w-5 md:h-5 text-gray-400 group-focus-within:text-green-800 transition-colors" />
                            <Input :type="passwordType" name="password" required autocomplete="current-password"
                                placeholder="••••••••"
                                class="bg-white/60 border-gray-200 h-11 md:h-13 pl-11 md:pl-12 pr-11 md:pr-12 rounded-xl focus:ring-2 focus:ring-green-800 focus:bg-white transition-all text-sm md:text-base" />
                            
                            <button type="button" @click="togglePasswordVisibility"
                                class="absolute right-0 top-0 h-full w-10 md:w-12 flex items-center justify-center text-gray-400 hover:text-green-800 transition-colors">
                                <Eye v-if="passwordType === 'password'" class="w-4 h-4 md:w-5 md:h-5" />
                                <EyeOff v-else class="w-4 h-4 md:w-5 md:h-5" />
                            </button>
                        </div>
                        <InputError :message="errors.password" />
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 py-1">
                        <label class="flex items-center gap-2 text-xs md:text-sm text-gray-600 font-semibold cursor-pointer">
                            <Checkbox name="remember" class="h-4 w-4 border-gray-300 data-[state=checked]:bg-green-800 rounded-md"/>
                            Remember Me
                        </label>
                        
                        <TextLink v-if="canResetPassword" :href="request()" class="text-xs md:text-sm text-green-800 font-bold hover:text-yellow-600 transition">
                            Forgot Password?
                        </TextLink>
                    </div>

                    <Button type="submit" class="w-full h-12 md:h-14 mt-2 md:mt-4 text-sm md:text-base font-black tracking-widest
                        bg-green-900 text-white 
                        hover:bg-[#FFCA52] hover:text-green-950 
                        rounded-xl transition-all duration-300 shadow-xl shadow-green-900/20 active:scale-[0.98]"
                        :disabled="processing">
                        <Spinner v-if="processing" />
                        <span v-else class="uppercase">Login Securely</span>
                    </Button>

                    <div v-if="canRegister" class="text-center text-xs md:text-sm text-gray-600 mt-4 md:mt-6">
                        Need access to the portal?
                        <TextLink :href="register()" class="text-green-900 font-black hover:underline ml-1">Create Account</TextLink>
                    </div>
                </Form>
            </div>

            <div class="bg-gray-50/50 py-3 md:py-4 px-6 md:px-8 border-t border-white/30 text-center">
                <p class="text-[8px] md:text-[10px] text-gray-500 font-bold tracking-[0.15em] md:tracking-[0.2em] uppercase">
                    Transparency • Integrity • Public Service
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