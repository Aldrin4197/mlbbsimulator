<template>
  <div class="flex flex-col items-center cursor-pointer w-20">
    <div
      @click="handleCardClick"
      class="w-full h-20 rounded overflow-hidden border-2 border-gray-600"
    >
      <img
        v-if="imageSrc"
        :src="imageSrc"
        alt="Ban Image"
        class="w-full h-full object-cover"
        draggable="false"
      />
      <div
        v-else
        class="flex items-center justify-center h-full bg-gray-800 text-white text-xs select-none text-center px-1"
      >
        Click to select Ban
      </div>

      <input
        type="file"
        accept="image/*"
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
  imageSrc?: string;
  label: string;
}>();

const emit = defineEmits<{ (e: 'image-select', url: string): void }>();

const { fileInput, handleCardClick, handleFileChange } = useMediaPicker(emit, 'image-select');
</script>
