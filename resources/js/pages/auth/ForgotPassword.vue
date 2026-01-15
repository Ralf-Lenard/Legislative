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
    
    defineProps<{
        status?: string;
    }>();
    </script>
    
    <template>
        <Head title="Forgot Password" />
    
        <div
            class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4"
            style="background-image: url('/images/townhall.jpg')"
        >
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    
            <div class="relative w-[1100px] max-w-full grid grid-cols-1 md:grid-cols-2 shadow-2xl rounded-3xl overflow-hidden bg-white">
                
                <div class="bg-[#0A3D1B] px-10 py-16 flex flex-col justify-center text-white">
                    <div class="flex items-center gap-4 mb-10">
                        <img src="/images/lg.jpg" class="w-20 h-20 object-cover rounded-full border-2 border-[#FFCA52] shadow-xl" alt="Municipality Logo" />
                        <div>
                            <p class="text-xl font-bold tracking-wide">MUNICIPAL GOVERNMENT</p>
                            <p class="text-sm text-white/80">Security & Recovery</p>
                        </div>
                    </div>
    
                    <h1 class="text-4xl font-extrabold leading-tight">
                        Reset Your <br /><span class="text-[#FFCA52]">Account Access</span>
                    </h1>
    
                    <p class="text-base mt-6 text-white/90">
                        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                    </p>
                    
                    <div class="mt-auto pt-10">
                        <p class="text-xs font-semibold tracking-widest text-[#FFCA52] uppercase">
                            Transparency • Integrity • Public Service
                        </p>
                    </div>
                </div>
    
                <div class="bg-white px-10 py-16 flex flex-col justify-center space-y-8">
                    <div>
                        <h2 class="text-3xl font-bold text-[#0A3D1B]">Forgot Password</h2>
                        <p class="text-gray-500 text-base mt-2">Enter your email to receive a recovery link</p>
                    </div>
    
                    <div
                        v-if="status"
                        class="p-4 rounded-lg bg-green-50 border border-green-200 text-sm font-medium text-green-700"
                    >
                        {{ status }}
                    </div>
    
                    <Form v-bind="email.form()" v-slot="{ errors, processing }" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="email" class="text-gray-700 font-medium">Email Address</Label>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                required
                                autocomplete="off"
                                autofocus
                                placeholder="email@example.com"
                                class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-12 text-base"
                            />
                            <InputError :message="errors.email" />
                        </div>
    
                        <Button
                            type="submit"
                            class="w-full h-12 text-lg font-bold bg-[#FFCA52] text-[#0A3D1B] hover:bg-[#E5B64A] transition duration-300 shadow-md"
                            :disabled="processing"
                            data-test="email-password-reset-link-button"
                        >
                            <Spinner v-if="processing" class="mr-2" />
                            <span v-else>SEND RESET LINK</span>
                        </Button>
                    </Form>
    
                    <div class="text-center text-base text-gray-600">
                        Remember your password?
                        <TextLink :href="login()" class="text-[#0A3D1B] font-bold hover:text-[#D49A20] transition-colors underline ml-1">Log in</TextLink>
                    </div>
                </div>
            </div>
        </div>
    </template>