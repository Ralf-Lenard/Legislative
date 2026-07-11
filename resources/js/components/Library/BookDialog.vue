<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
  >
    <div
      class="relative max-h-[90vh] w-full max-w-xl scale-100 transform overflow-y-auto rounded-xl border border-slate-100 bg-white p-8 opacity-100 shadow-2xl transition-all duration-300"
    >
      <div class="mb-6 flex items-start justify-between border-b pb-4">
        <div>
          <h2 class="text-2xl font-extrabold text-slate-900">
            {{ book ? 'Edit Book' : 'New Book' }}
          </h2>
          <p class="mt-1 text-sm text-slate-600">
            {{ book ? 'Update book details and cover.' : 'Enter details for a new library entry.' }}
          </p>
        </div>
        <button
          @click="closeModal"
          class="ml-4 rounded-full p-2 text-slate-600 transition-colors hover:bg-slate-100"
        >
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <form @submit.prevent="submit" class="space-y-5">

        <!-- Title -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">
            Book Title <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.title"
            type="text"
            required
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
          />
        </div>

        <!-- Author -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">
            Author <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.author"
            type="text"
            required
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
          />
        </div>

        <!-- Category -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">
            Category
          </label>
          <input
            v-model="form.category"
            type="text"
            placeholder="e.g. Fiction, Science, History"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
          />
        </div>

        <!-- Published Year -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">
            Published Year
          </label>
          <input
            v-model="form.published_year"
            type="number"
            min="0"
            placeholder="e.g. 2023"
            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
          />
        </div>

        <!-- Description -->
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">
            Summary / Description
          </label>
          <textarea
            v-model="form.description"
            rows="3"
            class="w-full resize-none rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
          ></textarea>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 pt-6">
          <button
            type="button"
            @click="closeModal"
            class="rounded-xl bg-slate-100 px-6 py-2.5 font-medium text-slate-700 hover:bg-slate-200"
          >
            Cancel
          </button>

          <button
            type="submit"
            :disabled="isLoading"
            class="rounded-xl bg-emerald-600 px-6 py-2.5 font-semibold text-white shadow-md shadow-emerald-500/20 hover:bg-emerald-700 disabled:opacity-50"
          >
            <span v-if="isLoading">Saving...</span>
            <span v-else>{{ book ? 'Update Book' : 'Save Book' }}</span>
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { reactive, ref, watch } from 'vue'

const props = defineProps<{
  isOpen: boolean
  book?: any | null
}>()

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'submitted'): void
}>()

const isLoading = ref(false)
const isImageDragging = ref(false)
const oldImage = ref<string | null>(null)
const imagePreviewUrl = ref<string | null>(null) // Dedicated ref for file preview

watch(
  () => props.book,
  (book) => {
    // We only reset the form if the modal is open or about to open
    if (!props.isOpen && !book) return 

    if (book) {
      form.title = book.title ?? ''
      form.author = book.author ?? ''
      form.category = book.category ?? ''
      form.published_year = book.published_year ?? ''
      form.description = book.description ?? ''
      form.image = null
      
      // FIX: Use the book.image directly as it appears in your table
      // If your table uses :src="book.image", do the same here.
      oldImage.value = book.image ? book.image : null
    } else {
      // Reset form for New Book
      Object.assign(form, {
        title: '',
        author: '',
        category: '',
        published_year: '',
        description: '',
        image: null,
      })
      oldImage.value = null
    }
    imagePreviewUrl.value = null
  },
  { immediate: true }
)

const closeModal = () => {
  // Cleanup preview URL to save memory
  if (imagePreviewUrl.value) URL.revokeObjectURL(imagePreviewUrl.value)
  emit('close')
}

const handleFileChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (file) {
    form.image = file
    // Create local preview
    imagePreviewUrl.value = URL.createObjectURL(file)
    oldImage.value = null
  }
}

const handleFileDrop = (e: DragEvent) => {
  const file = e.dataTransfer?.files?.[0]
  if (file?.type.startsWith('image/')) {
    form.image = file
    imagePreviewUrl.value = URL.createObjectURL(file)
    oldImage.value = null
  }
  isImageDragging.value = false
}

// Updated preview logic
const getImagePreview = () => {
  if (imagePreviewUrl.value) return imagePreviewUrl.value
  return oldImage.value || ''
}

const form = reactive({
  title: '',
  author: '',
  category: '',
  published_year: '' as string | number | null,
  description: '',
  image: null as File | null,
})
const submit = async () => {
    isLoading.value = true;
    const data = new FormData();

    // 🔁 Auto append all form fields
    Object.entries(form).forEach(([k, v]) => {
        if (v instanceof File) {
            data.append(k, v);
        } else if (v !== null) {
            data.append(k, String(v));
        }
    });

    // ✅ Keep old image if no new image uploaded
    if (!form.image && oldImage.value) {
        data.append('keep_image', '1');
    }

    const url = props.book?.id
        ? `/admin-library/${props.book.id}`
        : `/admin-library`;

    // ✅ METHOD SPOOFING FOR UPDATE
    if (props.book?.id) {
        data.append('_method', 'PUT');
    }

    // ✅ ALWAYS POST (NEVER PUT)
    router.visit(url, {
        method: 'post',
        data,
        forceFormData: true,
        onFinish: () => {
            isLoading.value = false;
            closeModal();
            emit('submitted');
        }
    });
};

</script>

<style scoped>
.overflow-y-auto::-webkit-scrollbar { display: none; }
.overflow-y-auto { -ms-overflow-style: none; scrollbar-width: none; }
</style>
