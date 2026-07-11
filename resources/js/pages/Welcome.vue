<template>
    <div class="min-h-screen bg-white">
        <Navbar />

        <section class="relative overflow-hidden bg-gray-50 px-4 pt-20 pb-16 md:pt-28 md:pb-20">
            <div
                class="absolute top-0 right-0 h-[300px] w-[300px] md:h-[500px] md:w-[500px] rounded-full bg-gradient-to-br from-yellow-400 to-transparent opacity-20 blur-3xl"
            ></div>
            <div
                class="absolute bottom-0 left-0 h-[250px] w-[250px] md:h-[400px] md:w-[400px] rounded-full bg-gradient-to-tr from-green-900 to-transparent opacity-10 blur-3xl"
            ></div>

            <div
                class="relative z-10 mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 lg:grid-cols-2"
            >
                <div class="space-y-6 text-center lg:text-left">
                    <span
                        class="inline-block rounded-full bg-green-100 px-4 py-2 text-[10px] md:text-xs font-bold tracking-widest text-green-900 uppercase shadow-sm"
                    >
                        Official Website
                    </span>

                    <h1 class="text-4xl sm:text-5xl md:text-6xl leading-tight font-black text-gray-900">
                        Sangguniang Bayan
                        <span class="text-green-800">Concepcion, Tarlac</span>
                    </h1>

                    <p class="text-lg md:text-xl font-medium text-gray-600">
                        Transparency • Integrity • Public Service
                    </p>

                    <div class="flex flex-col gap-4 pt-6 sm:flex-row sm:justify-center lg:justify-start">
                        <a
                            href="/citizens-charter/ordinances"
                            class="rounded-lg bg-green-800 px-8 py-3 text-center font-bold text-white shadow-lg transition hover:bg-green-900"
                        >
                            Explore
                        </a>
                        <a
                            href="#about"
                            class="rounded-lg border-2 border-green-900 px-8 py-3 text-center font-bold text-green-900 hover:bg-gray-50"
                        >
                            Learn More
                        </a>
                    </div>
                </div>

                <div class="block lg:block">
                    <div
                        class="relative h-64 sm:h-80 md:h-96 w-full overflow-hidden rounded-2xl shadow-2xl"
                    >
                        <img
                            v-if="pageContent?.welcome_image"
                            :src="pageContent.welcome_image"
                            alt="Welcome"
                            class="h-full w-full object-cover"
                        />
                       <div
                            class="absolute right-4 bottom-4 rounded-lg bg-white px-4 py-2 text-xs md:text-sm font-semibold text-gray-900"
                        >
                            Concepcion, Tarlac
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section
            id="leadership"
            class="relative overflow-hidden bg-white px-4 py-16 md:py-24"
        >
            <div class="mx-auto max-w-7xl">
                <div
                    class="relative overflow-hidden rounded-3xl md:rounded-[2.5rem] bg-gradient-to-br from-green-900 via-green-800 to-green-900 shadow-2xl"
                >
                    <div
                        class="absolute top-0 right-0 h-64 w-64 translate-x-1/2 -translate-y-1/2 rounded-full bg-yellow-400/10 blur-3xl"
                    ></div>

                    <div class="grid items-stretch gap-0 lg:grid-cols-5">
                        <div class="relative min-h-[350px] sm:min-h-[450px] lg:col-span-2">
                            <img
                                v-if="
                                    pageContent?.vice_mayor_image ||
                                    viceMayor?.image
                                "
                                :src="
                                    pageContent?.vice_mayor_image ??
                                    viceMayor?.image
                                "
                                :alt="viceMayor?.name"
                                class="h-full w-full object-cover object-top transition-transform duration-700 hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-green-900 via-transparent to-transparent lg:hidden"
                            ></div>

                            <div
                                class="pointer-events-none absolute inset-4 rounded-2xl border-2 border-yellow-400/30"
                            ></div>
                        </div>

                        <div
                            class="relative flex flex-col justify-center p-6 sm:p-10 md:p-16 lg:col-span-3"
                        >
                            <div class="mb-4 md:mb-6">
                                <span
                                    class="inline-block rounded-full bg-yellow-400 px-4 py-1 text-[10px] md:text-xs font-bold tracking-widest text-green-950 uppercase"
                                >
                                    Presiding Officer
                                </span>
                            </div>

                            <h2
                                class="mb-6 md:mb-8 text-3xl sm:text-4xl leading-tight font-black text-white md:text-5xl"
                            >
                                Message to the <br class="hidden sm:block" />
                                <span class="text-yellow-400"
                                    >People of Concepcion</span
                                >
                            </h2>

                            <div class="relative mb-8 md:mb-10">
                                <svg
                                    class="absolute -top-6 -left-4 md:-left-8 h-10 w-10 md:h-16 md:w-16 text-yellow-400/20"
                                    fill="currentColor"
                                    viewBox="0 0 32 32"
                                >
                                    <path
                                        d="M10 8v8H6v4h4v4H2v-8h4V8h4zm14 0v8h-4v4h4v4h-8v-8h4V8h4z"
                                    />
                                </svg>

                                <p
                                    class="relative z-10 line-clamp-6 text-lg md:text-xl leading-relaxed text-green-50/90 italic"
                                    v-html="pageContent?.vice_mayor_message?.replace(/\n/g, '<br>')"
                                >
                                </p>
                            </div>

                            <div
                                class="flex flex-col items-start justify-between gap-6 border-t border-white/10 pt-8 sm:flex-row sm:items-center"
                            >
                                <div>
                                    <p class="text-xl md:text-2xl font-bold text-white">
                                        {{ viceMayor?.name }}
                                    </p>
                                    <p
                                        class="text-sm md:font-medium tracking-wide text-yellow-400"
                                    >
                                        Vice Mayor, Concepcion Tarlac
                                    </p>
                                </div>

                                <button
                                    @click="isModalOpen = true"
                                    class="group relative flex w-full sm:w-auto items-center justify-center gap-3 overflow-hidden rounded-xl bg-yellow-400 px-8 py-4 font-bold text-green-950 transition-all hover:bg-white"
                                >
                                    Read Full Address
                                    <span
                                        class="transition-transform group-hover:translate-x-1"
                                        >→</span
                                    >
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="bg-gray-50 px-4 py-16 md:py-24">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                    <div class="lg:col-span-7">
                        <div
                            class="flex h-full flex-col justify-between rounded-3xl border border-gray-100 bg-white p-6 md:p-12 shadow-sm"
                        >
                            <div>
                                <div class="mb-4 flex items-center gap-3">
                                    <div class="h-1 w-12 bg-green-800"></div>
                                    <span
                                        class="text-[10px] md:text-xs font-bold tracking-[0.2em] text-green-800 uppercase"
                                        >Our Identity</span
                                    >
                                </div>
                                <h3
                                    class="mb-6 text-3xl md:text-4xl leading-tight font-black text-gray-900"
                                >
                                    The Sangguniang Bayan <br />
                                    <span class="text-green-700"
                                        >Service Beyond Self</span
                                    >
                                </h3>
                                <p
                                    class="mb-8 text-base md:text-lg leading-relaxed text-gray-600"
                                >
                                    {{ pageContent?.about_us }}
                                </p>
                            </div>

                            <div
                                class="relative h-48 sm:h-64 w-full overflow-hidden rounded-2xl shadow-lg"
                            >
                                <img
                                    v-if="pageContent?.about_us_image"
                                    :src="pageContent.about_us_image"
                                    class="h-full w-full object-cover transition-transform duration-1000 hover:scale-110"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-8 lg:col-span-5">
                        <div
                            class="group relative flex-1 overflow-hidden rounded-3xl border border-gray-100 bg-white p-8 md:p-10 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl"
                        >
                            <div
                                class="absolute top-0 right-0 h-32 w-32 translate-x-16 -translate-y-16 rounded-full bg-green-50 transition-transform group-hover:scale-110"
                            ></div>
                            <div class="relative z-10">
                                <div
                                    class="mb-6 flex h-14 w-14 md:h-16 md:w-16 items-center justify-center rounded-2xl bg-green-800 text-2xl md:text-3xl shadow-lg shadow-green-900/20"
                                >
                                    🎯
                                </div>
                                <h4
                                    class="mb-4 text-xl md:text-2xl font-bold text-gray-900"
                                >
                                    Our Mission
                                </h4>
                                <p class="text-sm md:text-base leading-relaxed text-gray-600">
                                    {{ pageContent?.mission }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="group relative flex-1 overflow-hidden rounded-3xl bg-green-900 p-8 md:p-10 shadow-xl transition-all hover:-translate-y-1 hover:shadow-2xl"
                        >
                            <div
                                class="absolute top-0 right-0 h-32 w-32 translate-x-16 -translate-y-16 rounded-full bg-white/5"
                            ></div>
                            <div class="relative z-10">
                                <div
                                    class="mb-6 flex h-14 w-14 md:h-16 md:w-16 items-center justify-center rounded-2xl bg-yellow-400 text-2xl md:text-3xl shadow-lg shadow-yellow-400/20"
                                >
                                    👁️
                                </div>
                                <h4 class="mb-4 text-xl md:text-2xl font-bold text-white">
                                    Our Vision
                                </h4>
                                <p class="text-sm md:text-base leading-relaxed text-green-50/80">
                                    {{ pageContent?.vision }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-gray-50 px-4 py-16 md:py-20">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 md:mb-16 space-y-4 text-center">
                    <span
                        class="text-xs font-bold tracking-widest text-green-900 uppercase"
                        >Browse</span
                    >
                    <h2 class="text-2xl md:text-3xl font-bold text-green-900">Key Sections</h2>
                    <div class="mx-auto h-1 w-20 md:w-24 bg-yellow-400"></div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <a
                        v-for="feature in features"
                        :key="feature.title"
                        :href="feature.href"
                        class="group flex flex-col rounded-xl border-t-4 border-yellow-400 bg-white p-6 md:p-8 shadow-lg transition hover:shadow-xl"
                    >
                        <div class="text-4xl md:text-5xl text-green-900 transition-transform group-hover:scale-110">
                            {{ feature.icon }}
                        </div>
                        
                        <h3 class="mt-4 text-lg md:text-xl font-extrabold text-gray-900">
                            {{ feature.title }}
                        </h3>
                        
                        <p class="mt-2 text-xs md:text-sm text-gray-600">
                            {{ feature.desc }}
                        </p>

                        <p class="mt-auto pt-6 inline-flex items-center text-sm font-bold text-green-900 group-hover:text-yellow-600">
                            View Details →
                        </p>
                    </a>
                </div>
            </div>
        </section>

        <section class="bg-gray-50 px-4 py-16 md:py-20">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 md:mb-16 text-center">
                    <span
                        class="text-xs font-bold tracking-widest text-green-900 uppercase"
                        >Gallery</span
                    >
                    <h2 class="text-2xl md:text-3xl font-bold text-green-900">Community & Events</h2>
                    <div class="mx-auto mt-2 h-1 w-20 md:w-24 bg-yellow-400"></div>
                </div>

                <div
                    class="columns-1 gap-4 space-y-4 sm:columns-2 lg:columns-3 xl:columns-4"
                >
                    <div
                        v-for="(img, i) in pageContent?.gallery_images"
                        :key="i"
                        class="break-inside-avoid"
                    >
                        <div
                            class="overflow-hidden rounded-2xl shadow-sm transition-transform duration-300 hover:scale-[1.02] hover:shadow-lg"
                        >
                            <img
                                :src="typeof img === 'string' ? img : img.src"
                                :alt="`Gallery image ${i + 1}`"
                                class="block h-auto w-full object-cover"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="px-4 py-16 md:py-20">
            <div
                class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 lg:grid-cols-2"
            >
                <div class="space-y-8 md:space-y-10">
                    <div>
                        <span
                            class="text-xs font-bold tracking-widest text-green-900 uppercase"
                            >Contact</span
                        >
                       <h2 class="text-2xl md:text-3xl font-bold text-green-900">Get In Touch</h2>
                        <p class="mt-2 text-sm md:text-base text-gray-600">
                            We are here to serve. Reach out for inquiries and
                            assistance.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div
                            v-for="contact in contactDetails"
                            :key="contact.title"
                            class="flex items-start gap-4"
                        >
                            <div
                                class="flex h-10 w-10 md:h-12 md:w-12 shrink-0 items-center justify-center rounded-full bg-yellow-400 shadow"
                            >
                                <span class="text-lg text-green-900">{{
                                    contact.icon
                                }}</span>
                            </div>
                            <div>
                                <p class="text-base md:text-lg font-bold text-gray-900">
                                    {{ contact.title }}
                                </p>
                                <p class="text-xs md:text-sm text-gray-700">
                                    {{ contact.detail }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex h-96 items-center justify-center overflow-hidden rounded-2xl border-4 border-gray-200 bg-gray-100 shadow-xl"
                >
                    <iframe
                        class="h-full w-full"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d492.365013920174!2d120.65524349999999!3d15.324464299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396c27808ce141f%3A0xff3616ce0f24c62c!2sConcepcion%20Legislative%20Building!5e1!3m2!1sen!2sph!4v1731084631234!5m2!1sen!2sph"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </section>

       <section id="feedback" class="relative overflow-hidden bg-white px-4 py-16 md:py-24">
            <div class="mx-auto max-w-7xl">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">
                    
                   <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-xl md:p-12 text-gray-900">
                    <div class="mb-8">
                        <span class="inline-block rounded-full bg-green-100 px-4 py-2 text-[10px] font-bold tracking-widest text-green-900 uppercase">
                            Public Consultation
                        </span>
                        <h2 class="mt-4 text-3xl font-black md:text-4xl">Submit Feedback</h2>
                        <p class="mt-2 text-gray-600">
                            Share your suggestions, concerns, or commendations regarding municipal services.
                        </p>
                    </div>

                    <form @submit.prevent="submitFeedback" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-2 ml-1">
                                Feedback Category
                            </label>
                            <select v-model="feedbackForm.category" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-4 text-sm focus:border-green-800 focus:bg-white focus:ring-0">
                                <option value="" disabled>Select a category</option>
                                <option value="suggestion">Legislative Suggestion</option>
                                <option value="concern">Community Concern</option>
                                <option value="commendation">Commendation</option>
                                <option value="other">Others</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-2 ml-1">
                                Your Message
                            </label>
                            <textarea v-model="feedbackForm.message" rows="6" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-4 text-sm focus:border-green-800 focus:bg-white focus:ring-0"
                                placeholder="Please provide as much detail as possible to help us take action..."></textarea>
                        </div>

                        <button type="submit"
                            class="group flex w-full items-center justify-center gap-3 rounded-xl bg-green-800 py-4 font-bold text-white shadow-lg transition-all hover:bg-green-900 active:scale-95">
                            Submit Feedback
                            <span class="transition-transform group-hover:translate-x-1">📩</span>
                        </button>
                    </form>
                </div>

                    <div class="flex flex-col justify-center space-y-8">
                        <div class="relative overflow-hidden rounded-3xl bg-green-900 p-8 text-white md:p-12">
                            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-yellow-400/10 blur-3xl"></div>
                            
                            <h3 class="relative z-10 text-2xl font-bold md:text-3xl">Participatory Governance.</h3>
                            <p class="relative z-10 mt-4 text-green-50/70 leading-relaxed">
                                The Sangguniang Bayan is dedicated to ensuring that the needs of Concepcion are met through active collaboration with its citizens. Your input helps us refine our policies and improve local legislation.
                            </p>

                            <div class="mt-8 space-y-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-400 font-bold text-green-900">✓</div>
                                    <p class="text-sm font-medium">Categorized for legislative action</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-400 font-bold text-green-900">✓</div>
                                    <p class="text-sm font-medium">Reviewed by the Office of the Secretary</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-400 font-bold text-green-900">✓</div>
                                    <p class="text-sm font-medium">Direct impact on community development</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border-2 border-dashed border-gray-200 p-8 text-center transition hover:border-green-800/30">
                            <p class="text-sm italic text-gray-500">
                                "Public service is a public trust."
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <Teleport to="body">
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div v-if="showSuccessModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showSuccessModal = false"></div>

                    <div class="relative w-full max-w-sm transform rounded-3xl bg-white p-8 text-center shadow-2xl transition-all">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
                            <span class="text-4xl">✅</span>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900">Message Sent!</h3>
                        <p class="mt-3 text-sm text-gray-600">Your feedback has been successfully submitted.</p>
                        <button @click="showSuccessModal = false" class="mt-8 w-full rounded-xl bg-green-800 py-3 font-bold text-white hover:bg-green-900">
                            Close
                        </button>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <Footer />
    </div>

    <Transition name="fade">
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto px-4 py-6 md:py-10"
        >
            <div
                @click="isModalOpen = false"
                class="fixed inset-0 bg-black/75 backdrop-blur-md"
            ></div>

            <div
                class="relative z-10 my-auto flex w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl lg:flex-row max-h-[90vh]"
            >
                <div
                    class="relative h-48 sm:h-64 lg:h-auto lg:w-2/5 shrink-0"
                >
                    <img
                        v-if="viceMayor?.image"
                        :src="viceMayor.image"
                        :alt="viceMayor.name"
                        class="h-full w-full object-cover object-top"
                    />
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-green-950/40 via-transparent to-transparent"
                    ></div>

                    <div class="absolute right-6 bottom-6 left-6 hidden sm:block">
                        <span
                            class="rounded bg-yellow-400 px-3 py-1 text-[10px] font-bold tracking-widest text-green-900 uppercase"
                        >
                            Presiding Officer
                        </span>
                    </div>
                </div>

                <div class="flex w-full flex-col lg:w-3/5 overflow-y-auto">
                    <button
                        @click="isModalOpen = false"
                        class="absolute top-4 right-4 z-20 flex h-8 w-8 md:h-10 md:w-10 items-center justify-center rounded-full bg-gray-100 text-gray-900 shadow-sm transition hover:bg-gray-200"
                    >
                        ✕
                    </button>

                    <div class="flex-grow p-6 md:p-12">
                        <div class="mb-6 md:mb-8">
                            <p
                                class="mb-1 text-[10px] font-bold tracking-[0.3em] text-green-800 uppercase"
                            >
                                Office of the Vice Mayor
                            </p>
                            <h2
                                class="text-2xl md:text-3xl leading-tight font-black text-gray-900"
                            >
                                Official Message to the People
                            </h2>
                            <div class="mt-3 h-1 w-12 bg-yellow-400"></div>
                        </div>

                        <div
                            class="prose prose-green max-w-none space-y-4 md:space-y-6 font-serif text-base md:text-lg leading-relaxed text-gray-700 italic"
                            v-html="pageContent?.vice_mayor_message?.replace(/\n/g, '<br>')"
                        ></div>

                        <div
                            v-if="props.viceMayor"
                            class="mt-8 md:mt-10 border-t border-gray-100 pt-6"
                        >
                            <p class="text-lg md:text-xl font-bold text-gray-900">
                                {{ props.viceMayor.name }}
                            </p>
                            <p class="text-xs md:text-sm font-medium text-green-800">
                                Vice Mayor, Concepcion Tarlac
                            </p>
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-gray-50 p-4 md:p-6 text-right">
                        <button
                            @click="isModalOpen = false"
                            class="w-full sm:w-auto rounded-lg bg-green-900 px-10 py-2.5 font-bold text-white shadow-md transition hover:bg-green-800"
                        >
                            Close Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import Footer from '@/components/Home/Footer.vue';
import Navbar from '@/components/Home/Navbar.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'; // Import the Inertia router

interface ViceMayor {
    name: string;
    image: string | null;
}

interface PageContent {
    welcome_image: string | null;
    about_us_image: string | null;
    vice_mayor_image: string | null;
    vice_mayor_message: string | null;
    about_us: string | null;
    mission: string | null;
    vision: string | null;
    gallery_images: string[];
}

interface Props {
    viceMayor?: ViceMayor | null;
    pageContent?: PageContent | null;
    canRegister: boolean;
    errors: Record<string, string>;
    flash: Record<string, string | null>;
}

const props = defineProps<Props>();

const isModalOpen = ref(false);
const features = [
    {
        icon: '📑',
        title: 'Citizen’s Charter',
        desc: 'Learn about the services, requirements, and procedures offered by the Sangguniang Panlungsod.',
        href: '/citizens-charter',
        },
    {
        icon: '📜',
        title: 'Ordinances',
        desc: 'Explore the definitive collection of local laws and municipal regulations.',
        href: '/citizens-charter/ordinances',
    },
    {
        icon: '✍️',
        title: 'Resolutions',
        desc: 'Review official expressions of the Council\'s will and policy decisions.',
        href: '/citizens-charter/resolutions',
    },
    {
        icon: '👥',
        title: 'Organizational Chart', // Changed "Organizational Chart" to something punchier
        desc: 'Get to know your elected officials and their legislative roles.',
        href: '/organizational-chart',
    },
    {
        icon: '📅',
        title: 'Legislative Sessions',
        desc: 'Stay informed with upcoming schedules and official minutes of past meetings.',
        href: '/legislative-sessions',
    },
    {
        icon: '📚', // Better icon for Library
        title: 'Legislative Library',
        desc: 'Search through our curated digital archive of books and research materials.',
        href: '/library',
    },
];
const contactDetails = [
    {
        icon: '📍',
        title: 'Address',
        detail: 'Legislative, Concepcion, Tarlac',
    },
    { icon: '📞', title: 'Phone', detail: '(045) 628-2127' },
    {
        icon: '📧',
        title: 'Email',
        detail: 'sbocontar@gmail.com',
    },
];

const showSuccessModal = ref(false);

const feedbackForm = ref({
    category: '',
    message: ''
});

const submitFeedback = () => {
    router.post('/feedback', feedbackForm.value, {
        onSuccess: () => {
            // Reset form
            feedbackForm.value = { category: '', message: '' };
            
            // CORRECT: Use .value to trigger the reactive state
            showSuccessModal.value = true; 
        },
        onError: (errors) => {
            console.error(errors);
        },
        preserveScroll: true,
    });
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@layer utilities {
  /* Hide scrollbar for Chrome, Safari and Opera */
  .no-scrollbar::-webkit-scrollbar {
    display: none;
  }

  /* Hide scrollbar for IE, Edge and Firefox */
  .no-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
  }
}
</style>