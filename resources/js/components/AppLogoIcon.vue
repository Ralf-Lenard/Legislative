<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

interface Props {
  class?: HTMLAttributes['class'];
}

const props = defineProps<Props>();
</script>

<template>
  <!-- Single root element -->
  <img
    src="/images/logo.jpg"
    alt="Logo"
    :class="props.class"
  />
</template>
