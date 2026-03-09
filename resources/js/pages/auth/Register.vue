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
    import { Eye, EyeOff, User, Mail, Phone, Calendar, MapPin, Lock } from 'lucide-vue-next'; 
    
    // State
    const passwordType = ref('password');
    const confirmPasswordType = ref('password');
    const agreedToTerms = ref(false);
    
    const togglePasswordVisibility = () => {
        passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
    };
    
    const toggleConfirmPasswordVisibility = () => {
        confirmPasswordType.value = confirmPasswordType.value === 'password' ? 'text' : 'password';
    };
    </script>
    
    <template>
        <Head title="Register" />
    
        <div class="h-svh w-full flex items-center justify-center bg-cover bg-center bg-no-repeat relative px-4 overflow-hidden text-black"
            style="background-image: url('/images/lg.jpg')">
            
            <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>
    
            <div class="relative w-full max-w-[850px] backdrop-blur-xl bg-white/85 text-black border border-white/40 shadow-2xl rounded-[1.5rem] md:rounded-[2rem] overflow-hidden flex flex-col max-h-[95vh]">
                
                <div class="h-1.5 bg-[#FFCA52] w-full shrink-0"></div>
    
                <div class="px-6 py-6 md:px-10 md:py-8 overflow-y-auto sm:overflow-visible">
                    
                    <div class="text-center mb-4">
                        <div class="flex justify-center mb-2">
                            <img src="/images/lg.jpg" class="w-14 h-14 object-cover rounded-full border-2 border-white shadow-sm" alt="Municipality Logo" />
                        </div>
                        <h1 class="text-xl md:text-2xl font-black text-gray-900">
                            Create Your <span class="text-green-800">Account</span>
                        </h1>
                    </div>
    
                    <Form v-bind="store.form()" :reset-on-success="['password', 'password_confirmation']" v-slot="{ errors, processing }" class="flex flex-col gap-4">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                            
                            <!-- Full Name -->
                            <div class="space-y-1">
                                <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Full Name</Label>
                                <div class="relative group">
                                    <User class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                    <Input type="text" name="name" required placeholder="Juan Dela Cruz"
                                        class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100"  />
                                </div>
                                <InputError :message="errors.name" class="text-[10px]" />
                            </div>
    
                            <!-- Email -->
                            <div class="space-y-1">
                                <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Email Address</Label>
                                <div class="relative group">
                                    <Mail class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                    <Input type="email" name="email" required placeholder="example@email.com"
                                        class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                </div>
                                <InputError :message="errors.email" class="text-[10px]" />
                            </div>
    
                            <!-- Contact -->
                            <div class="space-y-1">
                                <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Contact Number</Label>
                                <div class="relative group">
                                    <Phone class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                    <Input type="text" name="contact_number" required placeholder="09123456789" maxlength="11"
                                        class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                </div>
                                <InputError :message="errors.contact_number" class="text-[10px]" />
                            </div>
    
                            <!-- Birthdate -->
                            <div class="space-y-1">
                                <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Birthdate</Label>
                                <div class="relative group">
                                    <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                    <Input type="date" name="birthdate" required
                                        class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                </div>
                                <InputError :message="errors.birthdate" class="text-[10px]" />
                            </div>
    
                            <!-- Address -->
                            <div class="col-span-full space-y-1">
                                <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Current Address</Label>
                                <div class="relative group">
                                    <MapPin class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                    <Input type="text" name="address" required placeholder="Street, Barangay, Concepcion"
                                        class="bg-white/60 text-black border-gray-200 h-10 pl-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                </div>
                                <InputError :message="errors.address" class="text-[10px]" />
                            </div>
    
                            <!-- Password -->
                            <div class="space-y-1">
                                <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Password</Label>
                                <div class="relative group">
                                    <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                    <Input :type="passwordType" name="password" required placeholder="••••••••"
                                        class="bg-white/60 text-black border-gray-200 h-10 pl-9 pr-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                    <button type="button" @click="togglePasswordVisibility"
                                        class="absolute right-0 top-0 h-full w-9 flex items-center justify-center text-gray-700">
                                        <Eye v-if="passwordType === 'password'" class="w-3.5 h-3.5" />
                                        <EyeOff v-else class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                                
                            </div>
    
                            <!-- Confirm Password -->
                            <div class="space-y-1">
                                <Label class="text-[11px] md:text-xs text-gray-700 font-bold ml-1">Confirm Password</Label>
                                <div class="relative group">
                                    <Lock class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-700" />
                                    <Input :type="confirmPasswordType" name="password_confirmation" required placeholder="••••••••"
                                        class="bg-white/60 text-black border-gray-200 h-10 pl-9 pr-9 rounded-lg text-sm focus:ring-1 focus:ring-green-800 focus:bg-white placeholder:text-gray-450 placeholder:opacity-100" />
                                    <button type="button" @click="toggleConfirmPasswordVisibility"
                                        class="absolute right-0 top-0 h-full w-9 flex items-center justify-center text-gray-700">
                                        <Eye v-if="confirmPasswordType === 'password'" class="w-3.5 h-3.5" />
                                        <EyeOff v-else class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </div>
                               
                        </div>
                         <InputError :message="errors.password" class="text-[10px]" />
    
                        <!-- Terms -->
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
    
                        <!-- Submit -->
                        <Button type="submit"
                            class="w-full h-11 text-sm font-black tracking-widest bg-green-900 text-white hover:bg-[#FFCA52] hover:text-green-950 rounded-lg transition-all"
                            :disabled="processing || !agreedToTerms">
                            <Spinner v-if="processing" />
                            <span v-else>REGISTER SECURELY</span>
                        </Button>
    
                        <div class="text-center text-xs text-gray-600">
                            Already have an account?
                            <TextLink :href="login()" class="text-green-900 font-black hover:underline ml-1">
                                Log in
                            </TextLink>
                        </div>
    
                    </Form>
                </div>
    
                <div class="bg-gray-50/50 py-2 border-t border-white/30 text-center shrink-0">
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                        Transparency • Integrity • Public Service
                    </p>
                </div>
            </div>
        </div>
    </template>