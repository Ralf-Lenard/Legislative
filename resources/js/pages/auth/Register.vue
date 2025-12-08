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
    
    // Assuming Lucide icons for password toggle
    import { Eye, EyeOff } from 'lucide-vue-next'; 
    
    // State for password visibility toggles
    const passwordType = ref('password');
    const confirmPasswordType = ref('password');
    
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
    
            <div class="relative w-[1100px] max-w-full grid grid-cols-1 md:grid-cols-2 shadow-2xl shadow-black/30 rounded-3xl overflow-hidden bg-white">
                <div class="bg-[#0A3D1B] px-10 py-16 flex flex-col justify-center text-white">
                    <div class="flex items-center gap-4 mb-10">
                        <img src="/images/lg.jpg" class="w-20 h-20 object-cover rounded-full border-2 border-[#FFCA52] shadow-xl" alt="Municipality Logo" />
                        <div>
                            <p class="text-xl font-bold tracking-wide">MUNICIPAL GOVERNMENT</p>
                            <p class="text-sm">Local Services Access</p>
                        </div>
                    </div>
    
                    <h1 class="text-4xl font-extrabold leading-tight">
                        Register for <br /><span class="text-[#FFCA52]">Public Services Access</span>
                    </h1>
    
                    <p class="text-base mt-6 text-white/90">
                        Create your account to access online services, submit requests, and participate in local governance.
                    </p>
                    <p class="text-sm mt-3 font-semibold tracking-wider text-white/70">
                        Transparency • Integrity • Public Service
                    </p>
                </div>
    
                <div class="bg-white px-10 py-16 space-y-6">
                    <h2 class="text-3xl font-bold text-[#0A3D1B]">Create Your Account</h2>
                    <p class="text-gray-600 text-base -mt-4 mb-6">Enter your details to register</p>
    
                    <Form
                        v-bind="store.form()"
                        :reset-on-success="['password', 'password_confirmation']"
                        v-slot="{ errors, processing }"
                        class="flex flex-col gap-6"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            <div>
                                <Label class="mb-1 text-gray-700">Full Name</Label>
                                <Input
                                    type="text"
                                    name="name"
                                    required
                                    autocomplete="name"
                                    placeholder="Enter your full name"
                                    class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-11 text-base"
                                />
                                <InputError :message="errors.name" />
                            </div>
    
                            <div>
                                <Label class="mb-1 text-gray-700">Email Address</Label>
                                <Input
                                    type="email"
                                    name="email"
                                    required
                                    autocomplete="email"
                                    placeholder="email@example.com"
                                    class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-11 text-base"
                                />
                                <InputError :message="errors.email" />
                            </div>
    
                            <div>
                                <Label class="mb-1 text-gray-700">Contact Number</Label>
                                <Input
                                    type="text"
                                    name="contact_number"
                                    required
                                    placeholder="09XXXXXXXXX"
                                    maxlength="11"
                                    class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-11 text-base"
                                />
                                <InputError :message="errors.contact_number" />
                            </div>
    
                            <div>
                                <Label class="mb-1 text-gray-700">Birthdate</Label>
                                <Input
                                    type="date"
                                    name="birthdate"
                                    required
                                    class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-11 text-base"
                                />
                                <InputError :message="errors.birthdate" />
                            </div>
                            
                            <div class="col-span-full">
                                <Label class="mb-1 text-gray-700">Current Address</Label>
                                <Input
                                    type="text"
                                    name="address"
                                    required
                                    placeholder="House No., Street, Barangay, City/Municipality"
                                    class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-11 text-base"
                                />
                                <InputError :message="errors.address" />
                            </div>
    
                            <div>
                                <Label class="mb-1 text-gray-700">Password</Label>
                                <div class="relative">
                                    <Input
                                        :type="passwordType"
                                        name="password"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Enter secure password"
                                        class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-11 text-base pr-12"
                                    />
                                    <button type="button" @click="togglePasswordVisibility"
                                        class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-500 hover:text-[#0A3D1B] transition"
                                        :aria-label="passwordType === 'password' ? 'Show password' : 'Hide password'">
                                        <Eye v-if="passwordType === 'password'" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <InputError :message="errors.password" />
                            </div>
    
                            <div>
                                <Label class="mb-1 text-gray-700">Confirm Password</Label>
                                <div class="relative">
                                    <Input
                                        :type="confirmPasswordType"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        placeholder="Confirm your password"
                                        class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-11 text-base pr-12"
                                    />
                                    <button type="button" @click="toggleConfirmPasswordVisibility"
                                        class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-500 hover:text-[#0A3D1B] transition"
                                        :aria-label="confirmPasswordType === 'password' ? 'Show password' : 'Hide password'">
                                        <Eye v-if="confirmPasswordType === 'password'" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <InputError :message="errors.password_confirmation" />
                            </div>
                        </div>
                        <Button
                            type="submit"
                            class="w-full h-12 mt-4 text-lg font-bold
                                bg-[#FFCA52] text-[#0A3D1B]
                                hover:bg-[#D49A20] hover:text-[#0A3D1B]
                                transition duration-300 shadow-md hover:shadow-lg"
                            :disabled="processing"
                        >
                            <Spinner v-if="processing" class="mr-2" />
                            <span v-else>REGISTER SECURELY</span>
                        </Button>
                    </Form>
    
                    <div class="text-center text-base text-gray-600 mt-6">
                        Already have an account?
                        <TextLink :href="login()" class="text-[#0A3D1B] font-bold hover:text-[#FFCA52] underline">Log in</TextLink>
                    </div>
                </div>
            </div>
        </div>
    </template>