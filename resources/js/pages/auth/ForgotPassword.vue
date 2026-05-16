<script setup lang="ts">
    import InputError from '@/components/InputError.vue';
    import TextLink from '@/components/TextLink.vue';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Spinner } from '@/components/ui/spinner';
    import { login } from '@/routes';
    import { email } from '@/routes/password';
    import { Form, Head } from '@inertiajs/vue3';
    import { Mail, ArrowLeft } from 'lucide-vue-next'; 
    
    defineProps<{
        status?: string;
    }>();
    </script>
    <template>
        <Head title="Forgot Password" />
    
        <div class="h-svh w-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 overflow-hidden text-black"
            style="background-image: url('/images/lg.jpg')">
            
            <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
    
            <div class="absolute top-0 right-0 h-[300px] w-[300px] rounded-full bg-yellow-400 opacity-20 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 h-[300px] w-[300px] rounded-full bg-green-900 opacity-20 blur-3xl pointer-events-none"></div>
    
            <div class="relative w-full max-w-[500px] backdrop-blur-xl bg-white/85 text-black border border-white/40 shadow-2xl rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden transition-all duration-300">
                
                <div class="h-1.5 md:h-2 bg-[#FFCA52] w-full"></div>
    
                <div class="px-8 py-10 md:px-12 md:py-14 text-center">
    
                    <!-- Logo -->
                    <div class="flex justify-center mb-6">
                        <img src="/images/logo.jpg"
                            class="w-20 h-20 object-cover rounded-full border-4 border-white shadow-lg"
                            alt="Municipality Logo" />
                    </div>
    
                    <!-- Title -->
                    <div class="mb-8">
                        <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">
                            Account <span class="text-green-800">Recovery</span>
                        </h1>
    
                        <p class="text-sm md:text-base text-gray-600 mt-3 font-medium px-2">
                            Enter your email address and we'll send you a link to reset your password.
                        </p>
                    </div>
    
                    <!-- Status -->
                    <div v-if="status"
                        class="mb-6 p-4 text-xs md:text-sm text-green-700 bg-green-50 rounded-xl border border-green-300 backdrop-blur-sm font-medium">
                        {{ status }}
                    </div>
    
                    <!-- Form -->
                    <Form v-bind="email.form()" v-slot="{ errors, processing }"
                        class="flex flex-col gap-5 text-left">
    
                        <!-- Email Input -->
                        <div class="space-y-1.5">
                            <Label class="text-xs md:text-sm text-gray-700 font-bold ml-1">
                                Email Address
                            </Label>
    
                            <div class="relative group">
                                <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-700 group-focus-within:text-green-800 transition-colors" />
    
                                <Input type="email" name="email" required autofocus
                                    placeholder="example@gmail.com"
                                    class="bg-white text-black border-gray-200 h-13 pl-12 rounded-xl focus:ring-2 focus:ring-green-800 focus:bg-white transition-all placeholder:text-gray-450 placeholder:opacity-100" />
                            </div>
    
                            <InputError :message="errors.email" />
                        </div>
    
                        <!-- Submit -->
                        <Button type="submit"
                            class="w-full h-14 mt-2 text-sm md:text-base font-black tracking-widest
                            bg-green-900 text-white 
                            hover:bg-[#FFCA52] hover:text-green-950 
                            rounded-xl transition-all duration-300 shadow-xl shadow-green-900/20 active:scale-[0.98]"
                            :disabled="processing">
    
                            <Spinner v-if="processing" />
                            <span v-else class="uppercase">Send Reset Link</span>
                        </Button>
    
                        <!-- Back to Login -->
                        <div class="text-center mt-4">
                            <TextLink :href="login()"
                                class="inline-flex items-center gap-2 text-sm text-green-900 font-black hover:underline group">
    
                                <ArrowLeft class="w-4 h-4 text-green-900 group-hover:-translate-x-1 transition-transform" />
    
                                Back to Login
                            </TextLink>
                        </div>
    
                    </Form>
                </div>
    
                <!-- Footer -->
                <div class="bg-gray-50/50 py-4 px-8 border-t border-white/30 text-center">
                    <p class="text-[10px] text-gray-500 font-bold tracking-[0.2em] uppercase">
                        Security • Integrity • Service
                    </p>
                </div>
            </div>
        </div>
    </template>
    
    <style scoped>
    /* Smooth transition for status message */
    .v-enter-active, .v-leave-active {
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .v-enter-from, .v-leave-to {
      opacity: 0;
      transform: translateY(-10px);
    }
    </style>