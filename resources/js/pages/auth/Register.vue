<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { store } from '@/routes/register';
import { login } from '@/routes';
import { Eye, EyeOff, User, Mail, Phone, Calendar, MapPin, Lock, ChevronLeft } from 'lucide-vue-next';

// ----------------------
// PROPS (For reCAPTCHA)
// ----------------------
const props = defineProps<{
    recaptchaSiteKey: string;
}>();

const recaptchaSiteKey = props.recaptchaSiteKey;

// State
const passwordType = ref('password');
const confirmPasswordType = ref('password');
const agreedToTerms = ref(false);

// ----------------------
// FORM STATE (we own submission end-to-end, no <Form> component)
// ----------------------
const form = useForm({
    name: '',
    email: '',
    contact_number: '',
    birthdate: '',
    address: '',
    password: '',
    password_confirmation: '',
    recaptcha_token: '',
});

// store.form() gives us the { action, method } pair the route needs
const registerRoute = store.form();

const togglePasswordVisibility = () => {
    passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
};

const toggleConfirmPasswordVisibility = () => {
    confirmPasswordType.value = confirmPasswordType.value === 'password' ? 'text' : 'password';
};

// ----------------------
// RECAPTCHA LOGIC
// ----------------------
// Loaded at most once, even if this component mounts again (e.g. after an
// Inertia SPA navigation back to /register). Sharing the same promise avoids
// injecting the <script> tag twice and racing multiple grecaptcha clients.
let recaptchaScriptPromise: Promise<void> | null = null;

const loadRecaptchaScript = (): Promise<void> => {
    if (!recaptchaSiteKey) {
        return Promise.reject('Missing reCAPTCHA site key — check recaptchaSiteKey is being passed from the backend and RECAPTCHA_SITE_KEY is set in .env');
    }

    if (window.grecaptcha) {
        return Promise.resolve();
    }

    if (recaptchaScriptPromise) {
        return recaptchaScriptPromise;
    }

    recaptchaScriptPromise = new Promise((resolve, reject) => {
        const existing = document.querySelector<HTMLScriptElement>('script[data-recaptcha-loader]');
        if (existing) {
            existing.addEventListener('load', () => resolve());
            existing.addEventListener('error', () => reject('Failed to load reCAPTCHA script'));
            return;
        }

        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${recaptchaSiteKey}`;
        script.async = true;
        script.dataset.recaptchaLoader = 'true';
        script.onload = () => resolve();
        script.onerror = () => reject('Failed to load reCAPTCHA script');
        document.head.appendChild(script);
    });

    return recaptchaScriptPromise;
};

onMounted(() => {
    loadRecaptchaScript().catch((err) => console.error(err));
});

const getRecaptchaToken = (): Promise<string> => {
    return new Promise((resolve, reject) => {
        loadRecaptchaScript()
            .then(() => {
                window.grecaptcha.ready(() => {
                    window.grecaptcha.execute(recaptchaSiteKey, { action: 'register' })
                        .then((token: string) => resolve(token))
                        .catch((err: unknown) => reject(err));
                });
            })
            .catch((err) => reject(err));
    });
};

// ----------------------
// SUBMIT
// ----------------------
const recaptchaError = ref('');

const handleSubmit = async () => {
    recaptchaError.value = '';

    try {
        form.recaptcha_token = await getRecaptchaToken();
    } catch (err) {
        recaptchaError.value = 'reCAPTCHA failed to load. Please refresh and try again.';
        console.error('reCAPTCHA error:', err);
        return;
    }

    form.submit(registerRoute.method, registerRoute.action, {
        preserveScroll: true,
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Register" />

    <div class="h-svh w-full flex flex-col items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 overflow-hidden text-black"
        style="background-image: url('/images/lg.jpg')">
        
        <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

        <div class="relative w-full max-w-[850px] backdrop-blur-xl bg-white/85 text-black border border-white/40 shadow-2xl rounded-[1.5rem] md:rounded-[2rem] overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="h-1.5 bg-[#FFCA52] w-full shrink-0"></div>

            <div class="px-6 py-6 md:px-10 md:py-8 overflow-y-auto">
                
                <div class="text-center mb-4">
                    <div class="flex justify-center mb-2">
                        <img src="/images/logo.jpg" class="w-14 h-14 object-cover rounded-full border-2 border-white shadow-sm" alt="Municipality Logo" />
                    </div>
                    <h1 class="text-xl md:text-2xl font-black text-gray-900">
                        Create Your <span class="text-green-800">Account</span>
                    </h1>
                    <div class="mt-2">
                        <Link href="/" class="text-xs md:text-sm text-gray-600 font-bold hover:text-green-900 transition">
                            ← Back to Home
                        </Link>
                    </div>
                </div>

                <form @submit.prevent="handleSubmit" class="flex flex-col gap-4">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                        
                        <div class="space-y-1">
                            <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Full Name</Label>
                            <div class="relative group">
                                <User class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                <Input v-model="form.name" type="text" name="name" required placeholder="Juan Dela Cruz"
                                    class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100"  />
                            </div>
                            <InputError :message="form.errors.name" class="text-[10px]" />
                        </div>

                        <div class="space-y-1">
                            <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Email Address</Label>
                            <div class="relative group">
                                <Mail class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                <Input v-model="form.email" type="email" name="email" required placeholder="example@email.com"
                                    class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                            </div>
                            <InputError :message="form.errors.email" class="text-[10px]" />
                        </div>

                        <div class="space-y-1">
                            <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Contact Number</Label>
                            <div class="relative group">
                                <Phone class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                <Input v-model="form.contact_number" type="text" name="contact_number" required placeholder="09123456789" maxlength="11"
                                    class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                            </div>
                            <InputError :message="form.errors.contact_number" class="text-[10px]" />
                        </div>

                        <div class="space-y-1">
                            <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Birthdate</Label>
                            <div class="relative group">
                                <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                <Input v-model="form.birthdate" type="date" name="birthdate" required
                                    class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                            </div>
                            <InputError :message="form.errors.birthdate" class="text-[10px]" />
                        </div>

                        <div class="col-span-full space-y-1">
                            <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Current Address</Label>
                            <div class="relative group">
                                <MapPin class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                <Input v-model="form.address" type="text" name="address" required placeholder="Street, Barangay, Concepcion"
                                    class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                            </div>
                            <InputError :message="form.errors.address" class="text-[10px]" />
                        </div>

                        <div class="space-y-1">
                            <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Password</Label>
                            <div class="relative group">
                                <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                <Input v-model="form.password" :type="passwordType" name="password" required placeholder="••••••••"
                                    class="bg-white/60 text-black border-gray-200 h-10 pl-9 pr-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                <button type="button" @click="togglePasswordVisibility"
                                    class="absolute right-0 top-0 h-full w-9 flex items-center justify-center text-gray-700">
                                    <Eye v-if="passwordType === 'password'" class="w-3.5 h-3.5" />
                                    <EyeOff v-else class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Confirm Password</Label>
                            <div class="relative group">
                                <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                <Input v-model="form.password_confirmation" :type="confirmPasswordType" name="password_confirmation" required placeholder="••••••••"
                                    class="bg-white/60 text-black border-gray-200 h-10 pl-9 pr-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                <button type="button" @click="toggleConfirmPasswordVisibility"
                                    class="absolute right-0 top-0 h-full w-9 flex items-center justify-center text-gray-700">
                                    <Eye v-if="confirmPasswordType === 'password'" class="w-3.5 h-3.5" />
                                    <EyeOff v-else class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <InputError :message="form.errors.password" class="text-[10px]" />

                    <InputError v-if="recaptchaError" :message="recaptchaError" class="text-[10px]" />
                    <InputError v-else :message="form.errors.recaptcha_token" class="text-[10px]" />

                    <div class="flex items-center gap-2">
                        <input id="terms" type="checkbox" v-model="agreedToTerms" required
                            class="w-3.5 h-3.5 text-green-800 border-gray-300 rounded focus:ring-green-800 cursor-pointer" />
                        <label for="terms" class="text-[11px] text-gray-600 cursor-pointer">
                            I agree to the 
                            <a href="/terms-of-service" class="text-green-900 font-bold">Terms of Service</a> 
                            and 
                            <a href="/privacy-policy" class="text-green-900 font-bold">Privacy Policy</a>.
                        </label>
                    </div>

                    <Button type="submit"
                        class="w-full h-11 text-sm font-black tracking-widest bg-green-900 text-white hover:bg-[#FFCA52] hover:text-green-950 rounded-lg transition-all"
                        :disabled="form.processing || !agreedToTerms">
                        <Spinner v-if="form.processing" />
                        <span v-else>REGISTER SECURELY</span>
                    </Button>

                    <div class="text-center text-xs text-gray-600">
                        Already have an account?
                        <TextLink :href="login()" class="text-green-900 font-black hover:underline ml-1">
                            Log in
                        </TextLink>
                    </div>

                </form>
            </div>

            <div class="bg-gray-50/50 py-2 border-t border-white/30 text-center shrink-0">
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                    Transparency • Integrity • Public Service
                </p>
            </div>
        </div>
    </div>
</template>