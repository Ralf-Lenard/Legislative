<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';
    import { Button } from '@/components/ui/button';
    import { ArrowLeft, Lock, Eye, Database, Share2, Info } from 'lucide-vue-next';
    
    const goBack = () => {
        window.history.back();
    };
    </script>
    
    <template>
        <Head title="Privacy Policy" />
    
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
                    <div class="text-sm text-gray-500 font-medium">Effective Date: January 2026</div>
                </div>
    
                <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
                    <div class="bg-[#0A3D1B] px-8 py-10 text-white">
                        <div class="flex items-center gap-3 mb-2">
                            <Lock class="w-8 h-8 text-[#FFCA52]" />
                            <h1 class="text-3xl font-bold">Privacy Policy</h1>
                        </div>
                        <p class="text-white/80 text-lg">
                            Your privacy is our priority. Learn how we protect and manage your personal data.
                        </p>
                    </div>
    
                    <div class="p-8 md:p-12 space-y-12">
                        
                        <section>
                            <h2 class="text-xl font-bold text-[#0A3D1B] mb-4 flex items-center gap-2">
                                <Info class="w-5 h-5" /> Introduction
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                The Municipal Government is committed to protecting your personal information. This Privacy Policy explains how we collect, use, and safeguard the data you provide through our Public Services Access portal in accordance with the <strong>Data Privacy Act of 2012</strong>.
                            </p>
                        </section>
    
                        <section>
                            <h2 class="text-xl font-bold text-[#0A3D1B] mb-4 flex items-center gap-2">
                                <Database class="w-5 h-5" /> Information We Collect
                            </h2>
                            <p class="text-gray-600 mb-4">To provide efficient municipal services, we collect the following:</p>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600">
                                <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg border-l-4 border-[#FFCA52]">
                                    <strong>Personal Details:</strong> Name, Birthdate, Gender
                                </li>
                                <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg border-l-4 border-[#FFCA52]">
                                    <strong>Contact Info:</strong> Email, Phone Number, Address
                                </li>
                                <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg border-l-4 border-[#FFCA52]">
                                    <strong>Identity:</strong> Valid IDs and supporting documents
                                </li>
                                <li class="flex items-center gap-2 bg-gray-50 p-3 rounded-lg border-l-4 border-[#FFCA52]">
                                    <strong>Usage:</strong> IP address and login timestamps
                                </li>
                            </ul>
                        </section>
    
                        <section>
                            <h2 class="text-xl font-bold text-[#0A3D1B] mb-4 flex items-center gap-2">
                                <Eye class="w-5 h-5" /> How We Use Your Data
                            </h2>
                            <div class="space-y-4 text-gray-600 leading-relaxed">
                                <p>Your information is used strictly for:</p>
                                <ul class="list-disc ml-5 space-y-2">
                                    <li>Verifying your identity for official municipal requests.</li>
                                    <li>Processing applications for permits, clearances, and social services.</li>
                                    <li>Sending critical updates regarding your submitted requests or local emergencies.</li>
                                    <li>Improving the delivery of digital government services.</li>
                                </ul>
                            </div>
                        </section>
    
                        <section>
                            <h2 class="text-xl font-bold text-[#0A3D1B] mb-4 flex items-center gap-2">
                                <Share2 class="w-5 h-5" /> Data Disclosure & Sharing
                            </h2>
                            <p class="text-gray-600 leading-relaxed">
                                We <strong>do not sell</strong> your personal data to third parties. Disclosure only occurs when:
                            </p>
                            <div class="mt-4 p-5 bg-yellow-50 rounded-xl border border-yellow-100 text-sm text-gray-700 italic">
                                "Data may be shared with authorized government agencies if required by law, for public health safety, or as part of an official legal proceeding."
                            </div>
                        </section>
    
                        <section class="bg-[#0A3D1B]/5 p-6 rounded-2xl border border-[#0A3D1B]/10">
                            <h2 class="text-xl font-bold text-[#0A3D1B] mb-3">Data Security</h2>
                            <p class="text-gray-600 leading-relaxed">
                                We implement administrative, technical, and physical security measures to protect your data from unauthorized access, alteration, or disclosure. This includes encrypted connections (SSL) and restricted access to database servers.
                            </p>
                        </section>
    
                        <hr class="border-gray-100" />
    
                        <section>
                            <h2 class="text-lg font-bold text-[#0A3D1B] mb-2">Your Rights</h2>
                            <p class="text-sm text-gray-500">
                                You have the right to request access to your data, ask for corrections, or request deletion of your account. For privacy concerns, contact our <strong>Data Protection Officer</strong> at:
                            </p>
                            <p class="mt-2 text-[#0A3D1B] font-bold">privacy@municipality.gov</p>
                        </section>
                    </div>
    
                    <div class="bg-gray-50 px-8 py-6 flex justify-center border-t border-gray-100">
                        <Button 
                            @click="goBack"
                            class="bg-[#0A3D1B] hover:bg-[#125a29] text-white px-10 py-2 rounded-full font-bold transition-all shadow-md"
                        >
                            I AGREE TO THE PRIVACY POLICY
                        </Button>
                    </div>
                </div>
                
                <p class="text-center mt-8 text-gray-400 text-xs uppercase tracking-widest">
                    Protected by Government Data Security Standards
                </p>
            </div>
        </div>
    </template>