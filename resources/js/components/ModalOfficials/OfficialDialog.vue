<template>
    <div
      v-if="isOpen"
      class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm"
    >
      <div
        class="relative max-h-[90vh] w-full max-w-xl scale-100 transform overflow-y-auto rounded-xl border border-slate-100 bg-white p-8 opacity-100 shadow-2xl transition-all duration-300"
      >
        <!-- Header -->
        <div class="mb-6 flex items-start justify-between border-b pb-4">
          <div>
            <h2 class="text-2xl font-extrabold text-slate-900">
              {{ official ? 'Edit Official' : 'New Official' }}
            </h2>
            <p class="mt-1 text-sm text-slate-600">
              {{ official ? 'Update official details.' : 'Enter details for a new official.' }}
            </p>
          </div>
          <button
            @click="closeModal"
            class="ml-4 rounded-full p-2 text-slate-600 transition-colors hover:bg-slate-100"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
  
        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-5">
          <!-- Name -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">
              Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
            />
          </div>
  
          <!-- Position -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">
              Position <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.position"
              type="text"
              required
              class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
            />
          </div>
  
          <!-- Main Committee -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">
              Main Committee
            </label>
            <input
              v-model="form.main_committee"
              type="text"
              class="w-full rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
            />
          </div>
  
          <!-- Bio -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Bio</label>
            <textarea
              v-model="form.bio"
              rows="3"
              class="w-full resize-none rounded-xl border border-slate-300 px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
            ></textarea>
          </div>
  
          <!-- Image Upload -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">
              Upload Image
            </label>
            <div
              @dragover.prevent="isImageDragging = true"
              @dragleave.prevent="isImageDragging = false"
              @drop.prevent="handleFileDrop($event, 'image')"
              class="relative rounded-xl border-2 border-dashed p-6 transition-colors duration-200"
              :class="{
                'border-sky-500 bg-sky-50': isImageDragging,
                'border-slate-300 hover:border-sky-400': !isImageDragging
              }"
            >
              <input
                id="image-upload"
                type="file"
                accept="image/*"
                @change="handleFileChange($event, 'image')"
                class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
              />
              <div class="flex flex-col items-center justify-center text-center">
                <svg class="mb-2 h-8 w-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M4 16l8-8 4 4 8-8"/>
                </svg>
                <p class="text-sm font-medium text-slate-700">
                  <label for="image-upload" class="cursor-pointer font-bold text-sky-600 hover:underline">
                    Click to browse
                  </label> or drag and drop an image
                </p>
                <p class="mt-1 text-xs text-slate-500">Accepted formats: JPG, PNG, GIF (max 20MB)</p>
              </div>
            </div>
  
            <div v-if="form.image || oldImage" class="mt-4">
              <p class="mb-2 text-xs text-slate-500">Current Image Preview:</p>
              <img
                :src="getImagePreview()"
                class="h-20 w-20 rounded-lg border-2 border-slate-200 object-cover shadow-md"
              />
            </div>
          </div>
  
          <!-- Committees -->
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Committees</label>
            <div v-for="(c, index) in form.committees" :key="index" class="mb-3 flex gap-2 items-center">
              <input
                v-model="c.name"
                type="text"
                placeholder="Committee Name"
                class="w-2/3 rounded-xl border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
              />
              <input
                v-model="c.role"
                type="text"
                placeholder="Role"
                class="w-1/3 rounded-xl border border-slate-300 px-3 py-2 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"
              />
              <button type="button" @click="removeCommittee(index)" class="text-red-500 font-bold">×</button>
            </div>
            <button type="button" @click="addCommittee" class="mt-2 rounded-xl bg-emerald-600 px-4 py-1 text-white font-semibold hover:bg-emerald-700">
              Add Committee
            </button>
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
              <span v-if="isLoading" class="flex items-center gap-2">
                <svg
                  class="h-5 w-5 animate-spin text-white"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
              </span>
              <span v-else>{{ official ? 'Update Official' : 'Save Official' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { router } from '@inertiajs/vue3';
  import { reactive, ref, watchEffect } from 'vue';
  
  const props = defineProps<{
    isOpen: boolean;
    official?: any | null;
  }>();
  const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'submitted'): void;
  }>();
  
  const isLoading = ref(false);
  const isImageDragging = ref(false);
  
  const form = reactive({
    name: '',
    position: '',
    main_committee: '',
    bio: '',
    image: null as File | null,
    committees: [] as { name: string; role: string }[],
  });
  
  const oldImage = ref<string | null>(null);
  
  watchEffect(() => {
    if (!props.isOpen) return;
  
    if (props.official) {
      form.name = props.official.name || '';
      form.position = props.official.position || '';
      form.main_committee = props.official.main_committee || '';
      form.bio = props.official.bio || '';
      form.committees = props.official.committees?.map((c: any) => ({
        name: c.name,
        role: c.pivot.role,
      })) || [];
      form.image = null;
      oldImage.value = props.official.image ? `/storage/${props.official.image}` : null;
    } else {
      form.name = '';
      form.position = '';
      form.main_committee = '';
      form.bio = '';
      form.committees = [];
      form.image = null;
      oldImage.value = null;
    }
  });
  
  const closeModal = () => emit('close');
  
  const handleFileDrop = (e: DragEvent) => {
    const file = e.dataTransfer?.files?.[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) return alert('Please drop a valid image file.');
    form.image = file;
    oldImage.value = null;
    isImageDragging.value = false;
  };
  
  const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;
    form.image = file;
    oldImage.value = null;
  };
  
  const getImagePreview = () => {
    if (form.image && typeof window !== 'undefined') return URL.createObjectURL(form.image);
    if (oldImage.value) return oldImage.value;
    return '';
  };
  
  // Committee management
  const addCommittee = () => form.committees.push({ name: '', role: '' });
  const removeCommittee = (index: number) => form.committees.splice(index, 1);
  
  const submit = async () => {
    isLoading.value = true;
    const data = new FormData();
  
    data.append('name', form.name);
    data.append('position', form.position);
    data.append('main_committee', form.main_committee);
    data.append('bio', form.bio);
  
    if (form.image instanceof File) {
      data.append('image', form.image);
    } else if (!form.image && oldImage.value) {
      data.append('keep_image', '1'); // keep old image
    }
  
    // Append committees
    form.committees.forEach((c, index) => {
      data.append(`committees[${index}][name]`, c.name);
      data.append(`committees[${index}][role]`, c.role);
    });
  
    const url = props.official?.id ? `/officials/${props.official.id}` : '/officials';
  
    await router.post(url, data, {
      forceFormData: true,
      onSuccess: () => {
        emit('submitted');
        closeModal();
        router.reload();
      },
      onFinish: () => (isLoading.value = false),
    });
  };
  </script>
  