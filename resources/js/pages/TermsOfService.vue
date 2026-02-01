<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';
    import { Button } from '@/components/ui/button';
    import { ArrowLeft, ShieldCheck, Scale, FileText, UserCheck } from 'lucide-vue-next';
    
    // You can replace this with your actual routing helper
    const goBack = () => {
        window.history.back();
    };
    </script>
    
    <template>
        <Head title="Terms of Service" />
    
        <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8 flex items-center justify-between">
                    <button 
                        @click="goBack" 
                        class="flex items-center text-[#0A3D1B] font-semibold hover:text-[#D49A20] transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5 mr-2" />
                        Back
                    </button>
                    <div class="text-sm text-gray-500 font-medium">Last Updated: January 2026</div>
                </div>
    
                <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
                    <div class="bg-[#0A3D1B] px-8 py-10 text-white">
                        <h1 class="text-3xl font-bold">Terms of Service</h1>
                        <p class="mt-2 text-white/80 text-lg">
                            Please read these terms carefully before using our Public Services Access portal.
                        </p>
                    </div>
    
                    <div class="p-8 md:p-12 space-y-10">
                        
                        <section class="flex gap-4">
                            <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FFCA52]/20 text-[#0A3D1B]">
                                <UserCheck class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-[#0A3D1B] mb-3">1. Acceptance of Terms</h2>
                                <p class="text-gray-600 leading-relaxed">
                                    By creating an account and accessing the Municipal Government portal, you agree to comply with and be bound by these Terms of Service. If you do not agree to these terms, please refrain from using the digital services.
                                </p>
                            </div>
                        </section>
    
                        <section class="flex gap-4">
                            <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FFCA52]/20 text-[#0A3D1B]">
                                <ShieldCheck class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-[#0A3D1B] mb-3">2. Account Security & Accuracy</h2>
                                <p class="text-gray-600 leading-relaxed mb-4">
                                    You are responsible for maintaining the confidentiality of your login credentials. You agree to:
                                </p>
                                <ul class="list-disc ml-5 space-y-2 text-gray-600">
                                    <li>Provide true, accurate, and current information during registration.</li>
                                    <li>Notify the administration immediately of any unauthorized use of your account.</li>
                                    <li>Ensure that all documents uploaded are legitimate and not tampered with.</li>
                                </ul>
                            </div>
                        </section>
    
                        <section class="flex gap-4">
                            <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FFCA52]/20 text-[#0A3D1B]">
                                <Scale class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-[#0A3D1B] mb-3">3. Code of Conduct</h2>
                                <p class="text-gray-600 leading-relaxed">
                                    Users are prohibited from using this portal for any unlawful activity, including but not limited to: identity theft, fraud, distribution of malicious software, or attempting to bypass the security measures of the municipal server.
                                </p>
                            </div>
                        </section>
    
                        <section class="flex gap-4">
                            <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#FFCA52]/20 text-[#0A3D1B]">
                                <FileText class="w-6 h-6" />
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-[#0A3D1B] mb-3">4. Privacy & Data Protection</h2>
                                <p class="text-gray-600 leading-relaxed">
                                    Your personal data is protected under the <strong>Data Privacy Act</strong>. We only collect information necessary to provide municipal services. By using this portal, you consent to the collection and processing of your data as outlined in our Privacy Policy.
                                </p>
                            </div>
                        </section>
    
                        <hr class="border-gray-100" />
    
                        <div class="bg-gray-50 rounded-2xl p-6 text-center">
                            <h3 class="font-bold text-[#0A3D1B]">Questions about our Terms?</h3>
                            <p class="text-sm text-gray-500 mb-4">Our support team is here to help you understand your rights.</p>
                            <a href="mailto:support@municipality.gov" class="text-[#0A3D1B] font-bold hover:underline">
                                support@municipality.gov
                            </a>
                        </div>
                    </div>
    
                    <div class="bg-gray-50 px-8 py-6 flex justify-center border-t border-gray-100">
                        <Button 
                            @click="goBack"
                            class="bg-[#0A3D1B] hover:bg-[#125a29] text-white px-10 py-2 rounded-full font-bold transition-all shadow-md"
                        >
                            I UNDERSTAND
                        </Button>
                    </div>
                </div>
                
                <p class="text-center mt-8 text-gray-400 text-xs">
                    © 2026 Municipal Government. All Rights Reserved. Transparency • Integrity • Public Service
                </p>
            </div>
        </div>
    </template>