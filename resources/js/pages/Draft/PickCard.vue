<template>
  <div class="flex flex-col items-center cursor-pointer w-40">
    <div
      @click="handleCardClick"
      class="w-full h-24 w-24 bg-gray-800 rounded-lg overflow-hidden flex items-center justify-center"
    >
      <video
        v-if="videoSrc"
        :src="videoSrc"
        autoplay
        loop
        muted
        class="w-full h-full object-cover"
      />
      <span v-else class="text-gray-400">Pick</span>

      <input
        type="file"
        accept="video/*"
        ref="fileInput"
        @change="handleFileChange"
        class="hidden"
      />
    </div>
    <span class="mt-1 text-xs text-center text-gray-300">{{ label }}</span>
  </div>
</template>

<script lang="ts" setup>
import { defineProps, defineEmits } from 'vue';
import { useMediaPicker } from '@/composables/useMediaPicker';

const props = defineProps<{
  videoSrc?: string;
  label: string;
}>();

const emit = defineEmits<{ (e: 'video-select', url: string): void }>();

const { fileInput, handleCardClick, handleFileChange } = useMediaPicker(emit, 'video-select');
</script>
