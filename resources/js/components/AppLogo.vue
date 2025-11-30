<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

interface Props {
  class?: HTMLAttributes['class'];
}

const props = defineProps<Props>();
</script>

<template>
  <div class="flex items-center justify-center">
    <img
      src="/images/logo.jpg"
      alt="Logo"
      :class="props.class"
      class="rounded-full"
    />
  </div>
</template>
