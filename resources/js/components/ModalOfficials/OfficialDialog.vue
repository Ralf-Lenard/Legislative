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
            {{ official ? 'Edit Record' : 'New Record' }}
          </h2>
          <p class="mt-1 text-sm text-slate-600">
            {{ official ? 'Update details.' : 'Enter details for a new official or employee.' }}
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
        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">Record Type <span class="text-red-500">*</span></label>
          <div class="flex gap-4">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" v-model="form.type" value="official" class="text-emerald-600 focus:ring-emerald-500" />
              <span class="text-sm">Official</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="radio" v-model="form.type" value="employee" class="text-emerald-600 focus:ring-emerald-500" />
              <span class="text-sm">Employee</span>
            </label>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Name <span class="text-red-500">*</span></label>
            <input v-model="form.name" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
          </div>

          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Position <span class="text-red-500">*</span></label>
            <input v-model="form.position" type="text" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
          </div>
        </div>

        <div v-if="form.type === 'employee'">
          <label class="mb-2 block text-sm font-semibold text-slate-900">Division <span class="text-red-500">*</span></label>
          <select v-model="form.division" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            <option value="">Select Division</option>
            <option value="Office of the SB Secretary">Office of the SB Secretary</option>
            <option value="Public Library Service">Public Library Service</option>
            <option value="Legislative Research, Records & Archives">Legislative Research, Records & Archives</option>
            <option value="Support Services">Support Services</option>
            <option value="Office of the SB Secretary">Office of the SB Secretary</option>
          </select>
        </div>

        <template v-if="form.type === 'official'">
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Main Committee</label>
            <input v-model="form.main_committee" type="text" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
          </div>

          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-900">Bio</label>
            <textarea v-model="form.bio" rows="3" class="w-full resize-none rounded-xl border border-slate-300 px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
          </div>
        </template>

        <div>
          <label class="mb-2 block text-sm font-semibold text-slate-900">Upload Image</label>
          <div
            @dragover.prevent="isImageDragging = true"
            @dragleave.prevent="isImageDragging = false"
            @drop.prevent="handleFileDrop($event)"
            class="relative rounded-xl border-2 border-dashed p-6 transition-colors duration-200"
            :class="isImageDragging ? 'border-sky-500 bg-sky-50' : 'border-slate-300 hover:border-sky-400'"
          >
            <input id="image-upload" type="file" accept="image/*" @change="handleFileChange($event)" class="absolute inset-0 h-full w-full cursor-pointer opacity-0" />
            <div class="flex flex-col items-center justify-center text-center">
              <svg class="mb-2 h-8 w-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M4 16l8-8 4 4 8-8" />
              </svg>
              <p class="text-sm font-medium text-slate-700">Click or drag an image</p>
            </div>
          </div>
          <div v-if="form.image || oldImage" class="mt-4 flex items-center gap-3">
            <img :src="getImagePreview()" class="h-16 w-16 rounded-lg border object-cover" />
          </div>
        </div>

        <div v-if="form.type === 'official'">
          <label class="mb-2 block text-sm font-semibold text-slate-900">Committees</label>
          <div v-for="(c, index) in form.committees" :key="index" class="mb-4 space-y-2 rounded-lg border border-slate-100 p-3 bg-slate-50/50">
            <div class="flex gap-2">
              <input v-model="c.name" type="text" placeholder="Committee Name" class="w-1/2 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-emerald-500" />
              <input v-model="c.role" type="text" placeholder="Role" class="w-1/2 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-emerald-500" />
              <button type="button" @click="removeCommittee(index)" class="text-red-500 text-xl px-1">&times;</button>
            </div>
          </div>
          <button type="button" @click="addCommittee" class="text-sm rounded-lg bg-slate-200 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-300">
            + Add Committee
          </button>
        </div>

        <div class="mt-8 flex justify-end gap-3 border-t border-slate-200 pt-6">
          <button type="button" @click="closeModal" class="rounded-xl bg-slate-100 px-6 py-2.5 font-medium text-slate-700 hover:bg-slate-200">Cancel</button>
          <button
            type="submit"
            :disabled="isLoading"
            class="rounded-xl bg-emerald-600 px-6 py-2.5 font-semibold text-white shadow-md hover:bg-emerald-700"
          >
            {{ isLoading ? 'Saving...' : (official ? 'Update' : 'Save') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { reactive, ref, watchEffect } from 'vue';

const props = defineProps<{ isOpen: boolean; official?: any | null }>();
const emit = defineEmits(['close', 'submitted']);

const isLoading = ref(false);
const isImageDragging = ref(false);
const oldImage = ref<string | null>(null);

const form = reactive({
  type: 'official',
  name: '',
  position: '',
  division: '',
  main_committee: '',
  bio: '',
  image: null as File | null,
  committees: [] as { name: string; role: string; focus: string }[],
});

watchEffect(() => {
  if (!props.isOpen) return;
  if (props.official) {
    form.type = props.official.type || 'official';
    form.name = props.official.name || '';
    form.position = props.official.position || '';
    form.division = props.official.division || '';
    form.main_committee = props.official.main_committee || '';
    form.bio = props.official.bio || '';
    form.committees = props.official.committees?.map((c: any) => ({
      name: c.name,
      role: c.pivot?.role || '',
      focus: c.focus || '',
    })) || [];
    oldImage.value = props.official.image ? `/storage/${props.official.image}` : null;
  } else {
    resetForm();
  }
});

const resetForm = () => {
  form.type = 'official';
  form.name = '';
  form.position = '';
  form.division = '';
  form.main_committee = '';
  form.bio = '';
  form.committees = [];
  form.image = null;
  oldImage.value = null;
};

const handleFileChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file) form.image = file;
};

const handleFileDrop = (e: DragEvent) => {
  isImageDragging.value = false;
  const file = e.dataTransfer?.files?.[0];
  if (file?.type.startsWith('image/')) form.image = file;
};

const getImagePreview = () => (form.image ? URL.createObjectURL(form.image) : oldImage.value);

const addCommittee = () => form.committees.push({ name: '', role: '', focus: '' });
const removeCommittee = (index: number) => form.committees.splice(index, 1);
const closeModal = () => emit('close');

const submit = async () => {
  isLoading.value = true;
  const data = new FormData();

  data.append('type', form.type);
  data.append('name', form.name);
  data.append('position', form.position);

  if (form.type === 'employee') {
    // If division is empty, we send 'N/A' so the backend validation doesn't complain
    data.append('division', form.division || 'N/A');
  } else {
    data.append('main_committee', form.main_committee || '');
    data.append('bio', form.bio || '');
    form.committees.forEach((c, i) => {
      data.append(`committees[${i}][name]`, c.name);
      data.append(`committees[${i}][role]`, c.role);
    });
  }

  if (form.image) data.append('image', form.image);
  else if (oldImage.value) data.append('keep_image', '1');

  if (props.official?.id) data.append('_method', 'PUT');

  const url = props.official?.id ? `/admin-officials/${props.official.id}` : `/admin-officials`;

  router.post(url, data, {
    forceFormData: true,
    onSuccess: () => {
      closeModal();
      emit('submitted');
    },
    onError: (errors) => {
        console.error("Submission Errors:", errors);
        alert("Error: " + (errors.division || "Check form details"));
    },
    onFinish: () => (isLoading.value = false),
  });
};
</script>