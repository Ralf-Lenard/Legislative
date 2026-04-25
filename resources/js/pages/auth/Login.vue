<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Form, Head, Link } from "@inertiajs/vue3";
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

// ----------------------
// PROPS (FIXED ACCESS)
// ----------------------
const props = defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
    recaptchaSiteKey: string;
}>()

const recaptchaSiteKey = props.recaptchaSiteKey

const passwordType = ref('password');

const togglePasswordVisibility = () => {
    passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
};

const form = store.form();

// ----------------------
// RECAPTCHA LOAD
// ----------------------
onMounted(() => {
    const script = document.createElement('script');
    script.src = `https://www.google.com/recaptcha/api.js?render=${recaptchaSiteKey}`;
    script.async = true;
    document.head.appendChild(script);
});

// ----------------------
// GET TOKEN
// ----------------------
const getRecaptchaToken = () => {
    return new Promise((resolve, reject) => {
        if (!window.grecaptcha) {
            reject('reCAPTCHA not loaded')
            return
        }

        window.grecaptcha.ready(() => {
            window.grecaptcha.execute(recaptchaSiteKey, { action: 'login' })
                .then((token) => resolve(token))
                .catch((err) => reject(err))
        });
    });
};

// ----------------------
// SUBMIT
// ----------------------
const handleSubmit = async (submit: Function) => {
    const token = await getRecaptchaToken();
    form.recaptcha_token = token;
    submit();
};
</script>

<template>
    <Head title="Login" />

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
                        Sangguniang Bayan
                        <span class="block text-green-800">Concepcion Tarlac</span>
                    </h1>

                    <!-- 🔥 HOME LINK ADDED HERE -->
                    <div class="mt-2">
                        <Link href="/" class="text-xs md:text-sm text-gray-600 font-bold hover:text-green-900 transition">
                            ← Back to Home
                        </Link>
                    </div>
                </div>

                <Form
                    v-bind="store.form()"
                    :reset-on-success="['password']"
                    v-slot="{ errors, processing, submit }"
                    @submit.prevent="handleSubmit(submit)"
                    class="flex flex-col gap-4 md:gap-5 text-left"
                >

                    <!-- Email -->
                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">
                            Email Address
                        </Label>

                        <div class="relative group">
                            <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-700 group-focus-within:text-green-800 transition-colors" />
                            <Input
                                type="email"
                                name="email"
                                required
                                autocomplete="email"
                                placeholder="example@gmail.com"
                                class="bg-white text-black border-gray-200 
                                        h-11 md:h-13 pl-11 md:pl-12 rounded-xl 
                                        focus:ring-2 focus:ring-green-800 focus:bg-white 
                                        transition-all text-sm md:text-base"
                            />
                        </div>

                        <InputError :message="errors.email" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">
                            Password
                        </Label>

                        <div class="relative group">
                            <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-700 group-focus-within:text-green-800 transition-colors" />

                            <Input
                                :type="passwordType"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="bg-white text-black border-gray-200 
                                        h-11 md:h-13 pl-11 md:pl-12 rounded-xl 
                                        focus:ring-2 focus:ring-green-800 focus:bg-white 
                                        transition-all text-sm md:text-base"
                            />

                            <button type="button" @click="togglePasswordVisibility"
                                class="absolute right-0 top-0 h-full w-10 md:w-12 flex items-center justify-center text-gray-700 hover:text-green-800 transition-colors">
                                <Eye v-if="passwordType === 'password'" class="w-4 h-4 md:w-5 md:h-5" />
                                <EyeOff v-else class="w-4 h-4 md:w-5 md:h-5" />
                            </button>
                        </div>

                        <InputError :message="errors.password" />
                    </div>

                    <!-- Remember -->
                    <div class="flex justify-between items-center">
                        <label class="flex items-center gap-2 text-xs md:text-sm text-gray-700 font-semibold cursor-pointer">
                            <Checkbox name="remember" />
                            Remember Me
                        </label>

                        <TextLink v-if="canResetPassword"
                            :href="request()"
                            class="text-xs md:text-sm text-green-800 font-bold hover:text-yellow-600 transition">
                            Forgot Password?
                        </TextLink>
                    </div>

                    <!-- Submit -->
                    <Button type="submit"
                        class="w-full h-12 md:h-14 mt-2 text-sm md:text-base font-black tracking-widest
                        bg-green-900 text-white hover:bg-[#FFCA52] hover:text-green-950 
                        rounded-xl transition-all duration-300 shadow-xl"
                        :disabled="processing">

                        <Spinner v-if="processing" />
                        <span v-else class="uppercase">Login Securely</span>
                    </Button>

                    <!-- Register -->
                    <div v-if="canRegister" class="text-center text-xs md:text-sm text-gray-600 mt-4">
                        Need access?
                        <TextLink :href="register()" class="text-green-900 font-black ml-1">
                            Create Account
                        </TextLink>
                    </div>

                </Form>
            </div>
             <!-- Footer -->
            <div class="bg-gray-50/50 py-3 md:py-4 px-6 md:px-8 border-t border-white/30 text-center">
                <p class="text-[8px] md:text-[10px] text-gray-500 font-bold tracking-[0.15em] md:tracking-[0.2em] uppercase">
                    Transparency • Integrity • Public Service
                </p>
            </div>
        </div>
    </div>
</template>