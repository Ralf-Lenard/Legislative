<script setup lang="ts">
    import { ref } from 'vue';
    import InputError from '@/components/InputError.vue';
    import TextLink from '@/components/TextLink.vue';
    import { Button } from '@/components/ui/button';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import { Spinner } from '@/components/ui/spinner';
    import { Form, Head } from '@inertiajs/vue3';
    import { store } from '@/routes/register';
    import { login } from '@/routes';
    
    // Lucide icons
    import { Eye, EyeOff } from 'lucide-vue-next'; 
    
    // State
    const passwordType = ref('password');
    const confirmPasswordType = ref('password');
    const agreedToTerms = ref(false); // Track checkbox state
    
    const togglePasswordVisibility = () => {
        passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
    };
    
    const toggleConfirmPasswordVisibility = () => {
        confirmPasswordType.value = confirmPasswordType.value === 'password' ? 'text' : 'password';
    };
    </script>
    
    <template>
        <Head title="Register" />
    
        <div
            class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat relative"
            style="background-image: url('/images/townhall.jpg')"
        >
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    
            <div class="relative w-[1100px] max-w-full grid grid-cols-1 md:grid-cols-2 shadow-2xl rounded-3xl overflow-hidden bg-white">
                
                <div class="bg-[#0A3D1B] px-10 py-16 flex flex-col justify-center text-white">
                    <div class="flex items-center gap-4 mb-10">
                        <img src="/images/lg.jpg" class="w-20 h-20 object-cover rounded-full border-2 border-[#FFCA52] shadow-xl" alt="Municipality Logo" />
                        <div>
                            <p class="text-xl font-bold tracking-wide">MUNICIPAL GOVERNMENT</p>
                            <p class="text-sm text-white/80">Local Services Access</p>
                        </div>
                    </div>
    
                    <h1 class="text-4xl font-extrabold leading-tight">
                        Register for <br /><span class="text-[#FFCA52]">Public Services Access</span>
                    </h1>
    
                    <p class="text-base mt-6 text-white/90">
                        Create your account to access online services, submit requests, and participate in local governance.
                    </p>
                    
                    <div class="mt-auto pt-10">
                        <p class="text-xs font-semibold tracking-widest text-[#FFCA52] uppercase">
                            Transparency • Integrity • Public Service
                        </p>
                    </div>
                </div>
    
                <div class="bg-white px-10 py-12 md:py-16 space-y-6">
                    <div>
                        <h2 class="text-3xl font-bold text-[#0A3D1B]">Create Your Account</h2>
                        <p class="text-gray-500 text-base mt-1">Enter your details to register</p>
                    </div>
    
                    <Form
                        v-bind="store.form()"
                        :reset-on-success="['password', 'password_confirmation']"
                        v-slot="{ errors, processing }"
                        class="flex flex-col gap-5"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div class="space-y-1">
                                <Label class="text-gray-700 font-medium">Full Name</Label>
                                <Input type="text" name="name" required placeholder="Enter your full name" class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-11 text-base" />
                                <InputError :message="errors.name" />
                            </div>
    
                            <div class="space-y-1">
                                <Label class="text-gray-700 font-medium">Email Address</Label>
                                <Input type="email" name="email" required placeholder="email@example.com" class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-11 text-base" />
                                <InputError :message="errors.email" />
                            </div>
    
                            <div class="space-y-1">
                                <Label class="text-gray-700 font-medium">Contact Number</Label>
                                <Input type="text" name="contact_number" required placeholder="09XXXXXXXXX" maxlength="11" class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-11 text-base" />
                                <InputError :message="errors.contact_number" />
                            </div>
    
                            <div class="space-y-1">
                                <Label class="text-gray-700 font-medium">Birthdate</Label>
                                <Input type="date" name="birthdate" required class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-11 text-base" />
                                <InputError :message="errors.birthdate" />
                            </div>
                            
                            <div class="col-span-full space-y-1">
                                <Label class="text-gray-700 font-medium">Current Address</Label>
                                <Input type="text" name="address" required placeholder="House No., Street, Barangay, City/Municipality" class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-11 text-base" />
                                <InputError :message="errors.address" />
                            </div>
    
                            <div class="space-y-1">
                                <Label class="text-gray-700 font-medium">Password</Label>
                                <div class="relative">
                                    <Input :type="passwordType" name="password" required placeholder="Enter secure password" class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-11 text-base pr-12" />
                                    <button type="button" @click="togglePasswordVisibility" class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-400 hover:text-[#0A3D1B]">
                                        <Eye v-if="passwordType === 'password'" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <InputError :message="errors.password" />
                            </div>
    
                            <div class="space-y-1">
                                <Label class="text-gray-700 font-medium">Confirm Password</Label>
                                <div class="relative">
                                    <Input :type="confirmPasswordType" name="password_confirmation" required placeholder="Confirm your password" class="border-gray-300 focus:ring-[#0A3D1B] focus:border-[#0A3D1B] h-11 text-base pr-12" />
                                    <button type="button" @click="toggleConfirmPasswordVisibility" class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-400 hover:text-[#0A3D1B]">
                                        <Eye v-if="confirmPasswordType === 'password'" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <InputError :message="errors.password_confirmation" />
                            </div>
                        </div>
    
                        <div class="flex items-start gap-3 mt-2">
                            <div class="flex items-center h-5">
                                <input
                                    id="terms"
                                    type="checkbox"
                                    v-model="agreedToTerms"
                                    required
                                    class="w-4 h-4 text-[#0A3D1B] border-gray-300 rounded focus:ring-[#0A3D1B] cursor-pointer"
                                />
                            </div>
                            <label for="terms" class="text-sm text-gray-600 leading-tight cursor-pointer">
                                I agree to the 
                                <a href="/terms-of-service" class="text-[#0A3D1B] font-semibold hover:underline">Terms of Service</a> 
                                and 
                                <a href="/privacy-policy" class="text-[#0A3D1B] font-semibold hover:underline">Privacy Policy</a>.
                            </label>
                        </div>
    
                        <Button
                            type="submit"
                            class="w-full h-12 mt-2 text-lg font-bold bg-[#FFCA52] text-[#0A3D1B] hover:bg-[#E5B64A] transition duration-300 shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="processing || !agreedToTerms"
                        >
                            <Spinner v-if="processing" class="mr-2" />
                            <span v-else>REGISTER SECURELY</span>
                        </Button>
                    </Form>
    
                    <div class="text-center text-base text-gray-600 mt-6">
                        Already have an account?
                        <TextLink :href="login()" class="text-[#0A3D1B] font-bold hover:text-[#D49A20] transition-colors underline ml-1">Log in</TextLink>
                    </div>
                </div>
            </div>
        </div>
    </template>