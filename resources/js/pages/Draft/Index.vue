<template>
  <div class="min-h-screen text-white px-4 py-8 space-y-12 bg-gray-900">
    <h1 class="text-4xl font-bold text-center mb-6">
      Mobile Legends Draft Pick Simulator
    </h1>

    <div class="flex justify-center mb-6">
      <button
        @click="resetAll"
        class="px-6 py-2 rounded bg-red-600 hover:bg-red-700 transition"
      >
        Reset All
      </button>
    </div>

    <!-- Ban Phase 1 -->
    <Section title="Ban Phase 1" :count="6">
      <div class="flex flex-wrap gap-4 justify-center">
        <BanCard
          v-for="(img, idx) in banPhase1Images"
          :key="`ban1-${idx}`"
          :imageSrc="img ?? undefined"
          @image-select="(url) => handleImageChange(banPhase1Images, idx, url)"
        />
      </div>
    </Section>

    <!-- Pick Phase 1 -->
    <Section title="Pick Phase 1" :count="6">
      <div class="flex flex-wrap gap-4 justify-center">
        <PickCard
          v-for="(video, idx) in pickPhase1Videos"
          :key="`pick1-${idx}`"
          :videoSrc="video ?? undefined"
          @video-select="(url) => handleVideoChange(pickPhase1Videos, idx, url)"
        />
      </div>
    </Section>

    <!-- Ban Phase 2 -->
    <Section title="Ban Phase 2" :count="4">
      <div class="flex flex-wrap gap-4 justify-center">
        <BanCard
          v-for="(img, idx) in banPhase2Images"
          :key="`ban2-${idx}`"
          :imageSrc="img ?? undefined"
          @image-select="(url) => handleImageChange(banPhase2Images, idx, url)"
        />
      </div>
    </Section>

    <!-- Pick Phase 2 -->
    <Section title="Pick Phase 2" :count="4">
      <div class="flex flex-wrap gap-4 justify-center">
        <PickCard
          v-for="(video, idx) in pickPhase2Videos"
          :key="`pick2-${idx}`"
          :videoSrc="video ?? undefined"
          @video-select="(url) => handleVideoChange(pickPhase2Videos, idx, url)"
        />
      </div>
    </Section>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import BanCard from './BanCard.vue';
import PickCard from './PickCard.vue';

const banPhase1Images = ref<(string | null)[]>(Array(6).fill(null));
const banPhase2Images = ref<(string | null)[]>(Array(4).fill(null));
const pickPhase1Videos = ref<(string | null)[]>(Array(6).fill(null));
const pickPhase2Videos = ref<(string | null)[]>(Array(4).fill(null));

function handleImageChange(images: (string | null)[], index: number, url: string) {
  const newImages = [...images];
  newImages[index] = url;

  if (images === banPhase1Images.value) banPhase1Images.value = newImages;
  else if (images === banPhase2Images.value) banPhase2Images.value = newImages;
}

function handleVideoChange(videos: (string | null)[], index: number, url: string) {
  const newVideos = [...videos];
  newVideos[index] = url;

  if (videos === pickPhase1Videos.value) pickPhase1Videos.value = newVideos;
  else if (videos === pickPhase2Videos.value) pickPhase2Videos.value = newVideos;
}

function resetAll() {
  banPhase1Images.value = Array(6).fill(null);
  banPhase2Images.value = Array(4).fill(null);
  pickPhase1Videos.value = Array(6).fill(null);
  pickPhase2Videos.value = Array(4).fill(null);
}
</script>
