<template>
    <div class="min-h-screen bg-white">
        <Navbar />

        <section class="relative overflow-hidden bg-gray-50 px-4 pt-28 pb-20">
            <div
                class="absolute top-0 right-0 h-[500px] w-[500px] rounded-full bg-gradient-to-br from-yellow-400 to-transparent opacity-20 blur-3xl"
            ></div>
            <div
                class="absolute bottom-0 left-0 h-[400px] w-[400px] rounded-full bg-gradient-to-tr from-green-900 to-transparent opacity-10 blur-3xl"
            ></div>

            <div
                class="relative z-10 mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 lg:grid-cols-2"
            >
                <div class="space-y-6">
                    <span
                        class="inline-block rounded-full bg-green-100 px-4 py-2 text-xs font-bold tracking-widest text-green-900 uppercase shadow-sm"
                    >
                        Official Website
                    </span>

                    <h1 class="text-6xl leading-tight font-black text-gray-900">
                        Sangguniang Bayan
                        <span class="text-green-800">Concepcion</span>
                    </h1>

                    <p class="text-xl font-medium text-gray-600">
                        Transparency • Integrity • Public Service
                    </p>

                    <div class="flex flex-col gap-4 pt-6 sm:flex-row">
                        <a
                            href="/ordinances"
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

                    <div
                        class="mt-10 grid grid-cols-3 gap-6 border-t border-gray-200 pt-10"
                    >
                        <div>
                            <p class="text-4xl font-black text-green-900">
                                120+
                            </p>
                            <p class="text-sm text-gray-600">Ordinances</p>
                        </div>
                        <div>
                            <p class="text-4xl font-black text-green-900">8</p>
                            <p class="text-sm text-gray-600">Council Members</p>
                        </div>
                        <div>
                            <p class="text-4xl font-black text-green-900">24</p>
                            <p class="text-sm text-gray-600">Annual Sessions</p>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <div
                        class="relative h-96 w-full overflow-hidden rounded-2xl shadow-2xl"
                    >
                        <picture>
                            <source
                                media="(min-width:1024px)"
                                srcset="
                                    https://upload.wikimedia.org/wikipedia/commons/7/78/Concepcion_Municipal_Hall%2C_Tarlac%2C_Oct_2023.jpg
                                "
                            />
                            <source
                                media="(min-width:640px)"
                                srcset="
                                    https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg/1024px-Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg
                                "
                            />
                            <img
                                src="https://upload.wikimedia.org/wikipedia/commons/c/c0/Concepcion_Tarlac_Municipal_Hall_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg"
                                alt="Concepcion Municipal Hall"
                                class="h-full w-full object-cover"
                            />
                        </picture>
                        <div
                            class="absolute right-4 bottom-4 rounded-lg bg-white/90 px-4 py-2 text-sm font-semibold backdrop-blur-sm"
                        >
                            Concepcion, Tarlac
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="px-4 py-20">
            <div
                class="mx-auto grid max-w-7xl grid-cols-1 gap-8 lg:grid-cols-3 xl:grid-cols-4"
            >
                <div class="space-y-8 lg:col-span-2">
                    <div
                        class="grid transform items-center gap-10 rounded-2xl bg-gradient-to-r from-green-900 to-green-800 p-10 text-white shadow-2xl transition duration-300 hover:scale-[1.01] md:grid-cols-3"
                    >
                        <div class="flex justify-center">
                            <img
                                v-if="viceMayor?.image"
                                :src="viceMayor.image"
                                :alt="viceMayor.name"
                                class="h-64 w-64 rounded-full border-[6px] border-yellow-400 object-cover shadow-xl md:h-80 md:w-80"
                            />
                        </div>

                        <div class="space-y-6 md:col-span-2">
                            <div
                                class="font-serif text-7xl leading-none text-yellow-400 opacity-90"
                            >
                                “
                            </div>

                            <span
                                class="text-xs font-semibold tracking-[0.2em] text-yellow-300 uppercase"
                                >Leadership</span
                            >
                            <h2 class="text-3xl font-extrabold md:text-4xl">
                                Message From Our Presiding Officer
                            </h2>

                            <p
                                class="text-base leading-relaxed text-white/80 italic"
                            >
                                "Yone tlaen nat al ahe te erne haea eagi waei
                                merrite eaeaeitun thi enmne thowarwelaee ttaat
                                ttae ttaai thaiik haathalaeneud dae btenacri
                                eler salelotelee naeetae pae eeaw."
                            </p>

                            <button
                                @click="isModalOpen = true"
                                class="rounded-lg bg-yellow-400 px-8 py-3 font-bold text-green-900 shadow-lg transition hover:bg-white hover:shadow-xl active:scale-95"
                            >
                                Read Full Message →
                            </button>

                            <div
                                class="text-right font-serif text-7xl leading-none text-yellow-400 opacity-90"
                            >
                                ”
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8 lg:col-span-1 xl:col-span-2">
                    <div
                        class="space-y-4 rounded-xl border border-gray-100 bg-white p-6 shadow-md"
                    >
                        <span
                            class="text-xs font-bold tracking-widest text-green-800 uppercase"
                            >About Us</span
                        >
                        <h3 class="text-2xl font-black text-gray-900">
                            Sangguniang Bayan
                        </h3>

                        <div class="flex gap-4">
                            <div class="w-1/2 space-y-2">
                                <p
                                    class="text-sm leading-relaxed text-gray-700"
                                >
                                    The Sangguniang Bayan of Concepcion serves
                                    as the legislative body. Government's datal
                                    taeeuaet aebnered tb teredq qweas. Rteaeis
                                    wleaeae ttaet tyaea prateasramas. nteaee
                                    resepuael.
                                </p>
                            </div>
                            <div
                                class="flex h-40 w-1/2 items-center justify-center overflow-hidden rounded-lg bg-gray-100"
                            >
                                <img
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg/300px-Concepcion_Tarlac_Municipal_Hall_plaza_view_%28Timbol%2C_Concepcion%2C_Tarlac%3B_07-23-2023%29.jpg"
                                    alt="Municipal Hall"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        class="space-y-6 rounded-xl border border-gray-100 bg-white p-6 shadow-md"
                    >
                        <h3
                            class="text-center text-2xl font-extrabold text-gray-900"
                        >
                            Mission & Vision
                        </h3>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div
                                v-for="item in missionVision"
                                :key="item.title"
                                class="rounded-lg border p-4 shadow-sm transition hover:bg-green-50 hover:shadow-md"
                            >
                                <div
                                    class="mb-2 text-center text-4xl text-green-800"
                                >
                                    {{ item.icon }}
                                </div>
                                <h4
                                    class="text-center text-lg font-bold text-gray-900"
                                >
                                    {{ item.title }}
                                </h4>
                                <p
                                    class="mt-2 text-center text-sm text-gray-600"
                                >
                                    {{ item.desc }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-gray-50 px-4 py-20">
            <div class="mx-auto max-w-7xl">
                <div class="mb-16 space-y-4 text-center">
                    <span
                        class="text-xs font-bold tracking-widest text-green-900 uppercase"
                        >Browse</span
                    >
                    <h2 class="text-5xl font-black">Key Sections</h2>
                    <div class="mx-auto h-1 w-24 bg-yellow-400"></div>
                </div>

                <div
                    class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4"
                >
                    <a
                        v-for="feature in features"
                        :key="feature.title"
                        :href="feature.href"
                        class="group block rounded-xl border-t-4 border-yellow-400 bg-white p-8 shadow-lg transition hover:shadow-xl"
                    >
                        <div
                            class="text-5xl text-green-900 transition-transform group-hover:scale-110"
                        >
                            {{ feature.icon }}
                        </div>
                        <h3 class="mt-4 text-xl font-extrabold">
                            {{ feature.title }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ feature.desc }}
                        </p>

                        <p
                            class="mt-6 inline-flex items-center font-bold text-green-900 group-hover:text-yellow-500"
                        >
                            View Details →
                        </p>
                    </a>
                </div>
            </div>
        </section>

        <!-- <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-green-900">Initiatives</span>
            <h2 class="text-5xl font-black">Programs & Initiatives</h2>
            <div class="h-1 w-24 bg-yellow-400 mx-auto"></div>
          </div>
  
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a
              v-for="program in programs"
              :key="program.title"
              :href="program.href"
              class="bg-white rounded-xl p-8 border-2 border-green-900/10 hover:border-yellow-400 shadow-md transition group block"
            >
              <div class="text-6xl text-yellow-400 mb-4 group-hover:scale-110 transition-transform">
                {{ program.icon }}
              </div>
  
              <h3 class="text-xl font-bold">{{ program.title }}</h3>
              <p class="text-gray-600 text-sm mt-2">{{ program.desc }}</p>
  
              <p class="text-green-900 font-semibold mt-4 inline-block group-hover:text-yellow-400">
                Learn More
              </p>
            </a>
          </div>
        </div>
      </section> -->

      <section class="bg-gray-50 px-0 py-20">
    <div class="mx-auto max-w-7xl">
        <div class="mb-16 text-center">
            <span class="text-xs font-bold tracking-widest text-green-900 uppercase">Gallery</span>
            <h2 class="text-5xl font-black">Community & Events</h2>
            <div class="mx-auto h-1 w-24 bg-yellow-400 mt-2"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4 auto-rows-[200px]">
    <div
        v-for="(img, i) in pageContent?.gallery_images"
        :key="i"
        :class="[
            'cursor-pointer overflow-hidden rounded-lg shadow-lg transition hover:scale-[1.02] hover:shadow-xl',
            // Here we check if the image is wide or tall to span rows/cols
            img.width > img.height ? 'md:col-span-2' : 'md:row-span-2'
        ]"
    >
        <img
            :src="img.src"
            :alt="img.alt"
            class="h-full w-full object-cover"
        />
    </div>
</div>
    </div>
</section>


        <section class="px-4 py-20">
            <div
                class="mx-auto grid max-w-7xl grid-cols-1 items-center gap-12 md:grid-cols-2"
            >
                <div class="space-y-10">
                    <div>
                        <span
                            class="text-xs font-bold tracking-widest text-green-900 uppercase"
                            >Contact</span
                        >
                        <h2 class="mt-2 text-4xl font-black">Get In Touch</h2>
                        <p class="mt-2 text-gray-600">
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
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-400 shadow"
                            >
                                <span class="text-xl text-green-900">{{
                                    contact.icon
                                }}</span>
                            </div>
                            <div>
                                <p class="text-lg font-bold">
                                    {{ contact.title }}
                                </p>
                                <p class="text-sm text-gray-600">
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

        <Footer />
    </div>

    <Transition name="fade">
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto px-4 py-10"
        >
            <div
                @click="isModalOpen = false"
                class="fixed inset-0 bg-black/75 backdrop-blur-md"
            ></div>

            <div
                class="relative z-10 my-auto flex w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl md:flex-row"
            >
                <div
                    class="relative h-[400px] w-full bg-gray-200 md:h-auto md:w-2/5"
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

                    <div class="absolute right-6 bottom-6 left-6">
                        <span
                            class="rounded bg-yellow-400 px-3 py-1 text-xs font-bold tracking-widest text-green-900 uppercase"
                        >
                            Presiding Officer
                        </span>
                    </div>
                </div>

                <div class="flex w-full flex-col md:w-3/5">
                    <button
                        @click="isModalOpen = false"
                        class="absolute top-5 right-5 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-900 shadow-sm transition hover:bg-gray-200"
                    >
                        ✕
                    </button>

                    <div class="flex-grow p-8 md:p-12">
                        <div class="mb-8">
                            <p
                                class="mb-1 text-xs font-bold tracking-[0.3em] text-green-800 uppercase"
                            >
                                Office of the Vice Mayor
                            </p>
                            <h2
                                class="text-3xl leading-tight font-black text-gray-900"
                            >
                                Official Message to the People
                            </h2>
                            <div class="mt-3 h-1 w-12 bg-yellow-400"></div>
                        </div>

                        <div
                            class="prose prose-green max-w-none space-y-6 font-serif text-lg leading-relaxed text-gray-700 italic"
                        >
                            <p>
                                "Greetings to my fellow Concepcioneños. It is
                                with great pride and dedication that we present
                                this digital platform to ensure that governance
                                remains within your reach."
                            </p>

                            <p>
                                "Our commitment to transparency is not just a
                                promise, but a continuous journey. Through this
                                portal, we aim to bridge the gap between the
                                Sangguniang Bayan and the community we serve."
                            </p>

                            <p>
                                "Yone tlaen nat al ahe te erne haea eagi waei
                                merrite eaeaeitun thi enmne thowarwelaee ttaat
                                ttae ttaai thaiik haathalaeneud dae btenacri
                                eler salelotelee naeetae pae eeaw."
                            </p>
                        </div>

                        <div
                            v-if="props.viceMayor"
                            class="mt-10 border-t border-gray-100 pt-6"
                        >
                            <p class="text-xl font-bold text-gray-900">
                                {{ props.viceMayor.name }}
                            </p>
                            <p class="text-sm font-medium text-green-800">
                                Vice Mayor, Concepcion Tarlac
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end bg-gray-50 p-6">
                        <button
                            @click="isModalOpen = false"
                            class="rounded-lg bg-green-900 px-10 py-2.5 font-bold text-white shadow-md transition hover:bg-green-800"
                        >
                            Close Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script lang="ts" setup>
import Footer from '@/components/Home/Footer.vue';
import Navbar from '@/components/Home/Navbar.vue';
import { ref } from 'vue'; // Import ref

interface ViceMayor {
    name: string;
    image: string | null;
}

interface Props {
    viceMayor?: ViceMayor | null;
    canRegister: boolean;
    errors: Record<string, string>;
    flash: Record<string, string | null>;
}

const props = defineProps<Props>();

const isModalOpen = ref(false); // Modal state

const features = [
    // ADDED HREFs
    {
        icon: '📜',
        title: 'Ordinances',
        desc: 'Browse all approved municipal ordinances.',
        href: '/ordinances',
    },
    {
        icon: '✍️',
        title: 'Resolutions',
        desc: 'Access past resolutions and legislative decisions.',
        href: '/resolutions',
    },
    {
        icon: '👥',
        title: 'Members',
        desc: 'Meet our elected council members.',
        href: '/members',
    },
    {
        icon: '📅',
        title: 'Sessions',
        desc: 'View schedules and minutes of past sessions.',
        href: '/sessions',
    },
];

// const programs = [
//   // ADDED HREFs
//   { icon: '🌳', title: 'Green Concepcion', desc: 'Environmental programs and sustainability efforts.', href: '/programs/green-concepcion' },
//   { icon: '💡', title: 'Transparency Portal', desc: 'Access public records, budget, and governance data.', href: '/transparency' },
//   { icon: '🤝', title: 'Community Engagement', desc: 'Citizen participation through town hall consultations.', href: '/community-engagement' }
// ]

const missionVision = [
    {
        icon: '🎯',
        title: 'Mission',
        desc: 'To promote transparency, integrity, and public service while ensuring inclusive development in Concepcion, Tarlac.',
    },
    {
        icon: '🌟',
        title: 'Vision',
        desc: 'To be a model legislative body that empowers citizens, enhances governance, and fosters sustainable community growth.',
    },
];

const galleryImages = [
    {
        src: 'https://images.pexels.com/photos/3184300/pexels-photo-3184300.jpeg',
        alt: 'Community event 1',
    },
    {
        src: 'https://images.pexels.com/photos/3184312/pexels-photo-3184312.jpeg',
        alt: 'Community event 2',
    },
    {
        src: 'https://images.pexels.com/photos/3184396/pexels-photo-3184396.jpeg',
        alt: 'Community event 3',
    },
    {
        src: 'https://images.pexels.com/photos/3184436/pexels-photo-3184436.jpeg',
        alt: 'Community event 4',
    },
    {
        src: 'https://images.pexels.com/photos/3184454/pexels-photo-3184454.jpeg',
        alt: 'Community event 5',
    },
    {
        src: 'https://images.pexels.com/photos/3184460/pexels-photo-3184460.jpeg',
        alt: 'Community event 6',
    },
];

const contactDetails = [
    {
        icon: '📍',
        title: 'Address',
        detail: 'Municipal Hall, Concepcion, Tarlac',
    },
    { icon: '📞', title: 'Phone', detail: '(045) 923-4567' },
    {
        icon: '📧',
        title: 'Email',
        detail: 'sangguniangbayan@concepcion.gov.ph',
    },
];
</script>
