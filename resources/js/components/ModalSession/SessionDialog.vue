<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
    <div class="relative max-h-[90vh] w-full max-w-2xl scale-100 transform overflow-y-auto rounded-xl border border-slate-100 bg-white p-8 shadow-2xl transition-all duration-300">
      
      <div class="mb-6 flex items-start justify-between border-b pb-4">
        <div>
          <h2 class="text-2xl font-extrabold text-slate-900">{{ session ? 'Edit Session' : 'New Session' }}</h2>
          <p class="mt-1 text-sm text-slate-600">Enter legislative session details and manage the gallery.</p>
        </div>
        <button @click="closeModal" class="ml-4 rounded-full p-2 text-slate-600 hover:bg-slate-100">
          <X class="h-6 w-6" />
        </button>
      </div>

      <form @submit.prevent="submit" class="space-y-5">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Session Number *</label>
            <input v-model="form.session_number" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
          </div>
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Date of Session *</label>
            <input v-model="form.date_of_session" type="date" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
          </div>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">Session Title *</label>
          <input v-model="form.session_title" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">Session Type</label>
          <div class="flex gap-6">
            <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
              <input type="radio" value="Regular" v-model="form.session_type" class="text-emerald-600 focus:ring-emerald-500" /> Regular
            </label>
            <label class="flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
              <input type="radio" value="Special" v-model="form.session_type" class="text-emerald-600 focus:ring-emerald-500" /> Special
            </label>
          </div>
        </div>

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">Summary</label>
          <textarea v-model="form.summary" required rows="3" class="w-full resize-none rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
        </div>

        <hr class="border-slate-200" />

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">Session Images</label>

          <div v-if="existingImages.length > 0" class="mb-4">
            <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Current Gallery</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div v-for="(img, idx) in existingImages" :key="'ex-img-'+idx" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 p-2">
                <img :src="'/storage/' + img.file_path" class="h-12 w-12 rounded object-cover shadow-sm" />
                <input v-model="img.alt" class="flex-1 bg-transparent text-xs focus:outline-none" placeholder="Description..." />
                <button type="button" @click="removeExistingImg(idx)" class="text-slate-400 hover:text-red-500">
                  <X class="h-4 w-4" />
                </button>
              </div>
            </div>
          </div>

          <div @dragover.prevent="isDraggingImg = true" @dragleave.prevent="isDraggingImg = false" @drop.prevent="handleImgDrop"
            class="relative rounded-xl border-2 border-dashed p-6 transition-colors duration-200"
            :class="isDraggingImg ? 'border-emerald-500 bg-emerald-50' : 'border-slate-300 hover:border-emerald-400'">
            <input type="file" multiple accept="image/*" @change="handleImgSelect" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />
            <div class="flex flex-col items-center justify-center text-center">
              <UploadCloud class="mb-2 h-8 w-8 text-slate-400" />
              <p class="text-xs text-slate-600"><span class="font-bold text-emerald-600">Upload images</span> or drag & drop</p>
            </div>
          </div>

          <div v-if="tempImages.length > 0" class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div v-for="(img, index) in tempImages" :key="'temp-img-'+index" class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 p-2">
              <img :src="img.preview" class="h-12 w-12 rounded object-cover" />
              <input v-model="img.alt" placeholder="Alt text..." class="flex-1 rounded border bg-white px-2 py-1 text-xs focus:ring-1 focus:ring-emerald-500" />
              <button type="button" @click="removeTempImg(index)" class="text-slate-400 hover:text-red-500">
                <X class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>

        <hr class="border-slate-200" />

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">Session Videos</label>

          <div v-if="existingVideos.length > 0" class="mb-4">
            <p class="mb-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">Current Videos</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
              <div v-for="(vid, idx) in existingVideos" :key="'ex-vid-'+idx" class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 p-2">
                <div class="flex h-12 w-12 items-center justify-center rounded bg-slate-200 text-slate-500">
                  <Video class="h-6 w-6" />
                </div>
                <input v-model="vid.title" class="flex-1 bg-transparent text-xs focus:outline-none" placeholder="Video title..." />
                <button type="button" @click="removeExistingVid(idx)" class="text-slate-400 hover:text-red-500">
                  <X class="h-4 w-4" />
                </button>
              </div>
            </div>
          </div>

          <div @dragover.prevent="isDraggingVid = true" @dragleave.prevent="isDraggingVid = false" @drop.prevent="handleVidDrop"
            class="relative rounded-xl border-2 border-dashed p-6 transition-colors duration-200"
            :class="isDraggingVid ? 'border-emerald-500 bg-emerald-50' : 'border-slate-300 hover:border-emerald-400'">
            <input type="file" multiple accept="video/*" @change="handleVidSelect" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />
            <div class="flex flex-col items-center justify-center text-center">
              <UploadCloud class="mb-2 h-8 w-8 text-slate-400" />
              <p class="text-xs text-slate-600"><span class="font-bold text-emerald-600">Upload videos</span> or drag & drop</p>
            </div>
          </div>

          <div v-if="tempVideos.length > 0" class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div v-for="(vid, index) in tempVideos" :key="'temp-vid-'+index" class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50/50 p-2">
              <div class="flex h-12 w-12 items-center justify-center rounded bg-emerald-100 text-emerald-600">
                <Video class="h-6 w-6" />
              </div>
              <input v-model="vid.title" placeholder="Video title..." class="flex-1 rounded border bg-white px-2 py-1 text-xs focus:ring-1 focus:ring-emerald-500" />
              <button type="button" @click="removeTempVid(index)" class="text-slate-400 hover:text-red-500">
                <X class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>

        <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 pt-6">
          <button type="button" @click="closeModal" class="rounded-xl bg-slate-100 px-6 py-2.5 font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
          <button type="submit" :disabled="isLoading" class="rounded-xl bg-emerald-600 px-6 py-2.5 font-semibold text-white shadow-md hover:bg-emerald-700 disabled:opacity-50">
            <span v-if="isLoading" class="flex items-center gap-2"><Loader2 class="h-5 w-5 animate-spin" /> Saving...</span>
            <span v-else>{{ session ? 'Update Session' : 'Save Session' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { reactive, ref, watchEffect } from 'vue'
import { X, Loader2, UploadCloud, Video } from 'lucide-vue-next'

const props = defineProps<{ isOpen: boolean; session?: any | null }>()
const emit = defineEmits(['close', 'submitted'])

const isLoading = ref(false)
const isDraggingImg = ref(false)
const isDraggingVid = ref(false)

const existingImages = ref<any[]>([])
const tempImages = ref<any[]>([])

const existingVideos = ref<any[]>([])
const tempVideos = ref<any[]>([])

const form = reactive({
  session_number: '',
  session_title: '',
  date_of_session: '',
  session_type: 'Regular',
  summary: '',
})

// Image Logic
const addImages = (files: File[]) => {
  files.forEach(file => {
    if (file.type.startsWith('image/')) {
      tempImages.value.push({ file, alt: '', preview: URL.createObjectURL(file) })
    }
  })
}
const handleImgSelect = (e: any) => addImages(Array.from(e.target.files))
const handleImgDrop = (e: any) => { isDraggingImg.value = false; addImages(Array.from(e.dataTransfer.files)) }
const removeTempImg = (i: number) => { URL.revokeObjectURL(tempImages.value[i].preview); tempImages.value.splice(i, 1) }
const removeExistingImg = (i: number) => existingImages.value.splice(i, 1)

// Video Logic
const addVideos = (files: File[]) => {
  files.forEach(file => {
    if (file.type.startsWith('video/')) {
      tempVideos.value.push({ file, title: file.name })
    }
  })
}
const handleVidSelect = (e: any) => addVideos(Array.from(e.target.files))
const handleVidDrop = (e: any) => { isDraggingVid.value = false; addVideos(Array.from(e.dataTransfer.files)) }
const removeTempVid = (i: number) => tempVideos.value.splice(i, 1)
const removeExistingVid = (i: number) => existingVideos.value.splice(i, 1)

watchEffect(() => {
  if (!props.isOpen) return

  if (props.session) {
    form.session_number = props.session.session_number || ''
    form.session_title = props.session.session_title || ''
    
    if (props.session.date_of_session) {
      form.date_of_session = props.session.date_of_session.split('T')[0]
    }

    form.session_type = props.session.session_type || 'Regular'
    form.summary = props.session.summary || ''
    
    // Media hydration
    existingImages.value = props.session.images ? JSON.parse(JSON.stringify(props.session.images)) : []
    existingVideos.value = props.session.videos ? JSON.parse(JSON.stringify(props.session.videos)) : []
    tempImages.value = []
    tempVideos.value = []
  } else {
    form.session_number = ''; form.session_title = ''; form.summary = ''
    form.date_of_session = new Date().toISOString().split('T')[0]
    form.session_type = 'Regular'
    existingImages.value = []; tempImages.value = []
    existingVideos.value = []; tempVideos.value = []
  }
})

const closeModal = () => emit('close')

const submit = async () => {
  isLoading.value = true
  const data = new FormData()

  // Standard fields
  Object.entries(form).forEach(([key, val]) => {
    if (val !== null) data.append(key, String(val))
  })

  // IMAGES
  existingImages.value.forEach((img, i) => {
    data.append(`existing_images[${i}][file_path]`, img.file_path)
    data.append(`existing_images[${i}][alt]`, img.alt || '')
  })
  tempImages.value.forEach((img, i) => {
    data.append(`images[${i}][file]`, img.file)
    data.append(`images[${i}][alt]`, img.alt || '')
  })

  // VIDEOS
  existingVideos.value.forEach((vid, i) => {
    data.append(`existing_videos[${i}][file_path]`, vid.file_path)
    data.append(`existing_videos[${i}][title]`, vid.title || '')
  })
  tempVideos.value.forEach((vid, i) => {
    data.append(`videos[${i}][file]`, vid.file)
    data.append(`videos[${i}][title]`, vid.title || '')
  })

  const url = props.session?.id ? `/admin-sessions/${props.session.id}` : `/admin-sessions`

  if (props.session?.id) {
    data.append('_method', 'PUT')
  }

  router.visit(url, {
    method: 'post',
    data,
    forceFormData: true,
    preserveState: false,
    onFinish: () => {
      isLoading.value = false
      closeModal()
    }
  })
}
</script>

<style scoped>
  /* Hide scrollbar for Chrome, Safari and Opera */
  .overflow-y-auto::-webkit-scrollbar {
      display: none;
  }
  
  /* Hide scrollbar for IE, Edge and Firefox */
  .overflow-y-auto {
      -ms-overflow-style: none;  /* IE and Edge */
      scrollbar-width: none;  /* Firefox */
  }
  </style>