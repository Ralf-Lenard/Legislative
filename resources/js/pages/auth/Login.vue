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
    
    // Assuming you have Lucide icons available as in your previous components
    import { Eye, EyeOff } from 'lucide-vue-next'; 
        
    import { store } from "@/routes/login";
    import { register } from "@/routes";
    import { request } from "@/routes/password";
        
    defineProps<{
        status?: string;
        canResetPassword: boolean;
        canRegister: boolean;
    }>();
    
    // State for password visibility toggle
    const passwordType = ref('password');
    
    const togglePasswordVisibility = () => {
        passwordType.value = passwordType.value === 'password' ? 'text' : 'password';
    };
    </script>

   <template>
    <Head title="Login" />

    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
        style="background-image: url('/images/townhall.jpg')">
            
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        <div class="relative w-[950px] max-w-full grid grid-cols-1 md:grid-cols-2 shadow-2xl shadow-black/30 rounded-3xl overflow-hidden bg-white">

            <div class="bg-[#0A3D1B] px-10 py-16 flex flex-col justify-center text-white">
                
                <div class="flex items-center gap-4 mb-10">
                    <img src="/images/lg.jpg" class="w-20 h-20 object-cover rounded-full border-2 border-[#FFCA52] shadow-xl" alt="Municipality Logo" />
                    <div>
                        <p class="text-xl font-bold tracking-wide">MUNICIPAL GOVERNMENT</p>
                        <p class="text-sm">Local Services Access</p>
                    </div>
                </div>

                <h1 class="text-4xl font-extrabold leading-tight">
                    Login to Access<br />Your Public Profile
                </h1>

                <p class="text-base mt-6 text-white/90">
                    Securely access online services, track ordinance updates, and manage public records.
                </p>
                <p class="text-sm mt-3 font-semibold tracking-wider text-white/70">
                    Transparency • Integrity • Public Service
                </p>
            </div>

            <div class="bg-white px-10 py-16 space-y-6">

                <h2 class="text-3xl font-bold text-[#0A3D1B]">Account Access</h2>
                <p class="text-gray-600 text-base -mt-4 mb-6">Please enter your registered email and password</p>

                <div v-if="status" class="p-3 text-sm text-green-700 bg-green-50 rounded-lg border border-green-300">
                    {{ status }}
                </div>

                <Form v-bind="store.form()" :reset-on-success="['password']" v-slot="{ errors, processing }" class="flex flex-col gap-6">

                    <div>
                        <Label class="mb-1 text-gray-700">Email Address</Label>
                        <Input type="email" name="email" required autocomplete="email"
                            placeholder="email@example.com"
                            class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-12 text-base" />
                        <InputError :message="errors.email" />
                    </div>

                    <div>
                        <Label class="mb-1 text-gray-700">Password</Label>
                        <div class="relative">
                            <Input :type="passwordType" name="password" required autocomplete="current-password"
                                placeholder="Enter password"
                                class="border-[#0A3D1B]/40 focus:border-[#0A3D1B] h-12 text-base pr-12" />
                            
                            <button type="button" @click="togglePasswordVisibility"
                                class="absolute right-0 top-0 h-full w-12 flex items-center justify-center text-gray-500 hover:text-[#0A3D1B] transition duration-150"
                                :aria-label="passwordType === 'password' ? 'Show password' : 'Hide password'">
                                <Eye v-if="passwordType === 'password'" class="w-5 h-5" />
                                <EyeOff v-else class="w-5 h-5" />
                            </button>
                        </div>
                        <InputError :message="errors.password" />
                    </div>

                    <div class="flex justify-between items-center mt-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700 font-medium cursor-pointer">
                            <Checkbox name="remember" class="border-[#0A3D1B] data-[state=checked]:bg-[#0A3D1B] data-[state=checked]:text-white"/>
                            Remember Me
                        </label>
                        
                        <TextLink v-if="canResetPassword" :href="request()" class="text-sm text-[#0A3D1B] font-medium hover:text-[#FFCA52] transition">
                            Forgot Password?
                        </TextLink>
                    </div>

                    <Button type="submit" class="w-full h-12 mt-6 text-lg font-bold 
                        bg-[#FFCA52] text-[#0A3D1B] 
                        hover:bg-[#D49A20] hover:text-[#0A3D1B] 
                        transition duration-300 shadow-md hover:shadow-lg"
                        :disabled="processing">
                        <Spinner v-if="processing" />
                        <span v-else>LOGIN SECURELY</span>
                    </Button>

                    <div v-if="canRegister" class="text-center text-base text-gray-600 mt-6">
                        Don't have an account?
                        <TextLink :href="register()" class="text-[#0A3D1B] font-bold hover:text-[#FFCA52] underline">Create Account</TextLink>
                    </div>

                </Form>
            </div>
        </div>
    </div>
</template>
    