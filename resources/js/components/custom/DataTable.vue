<template>
  <div class="overflow-x-auto">
    <table class="min-w-full border border-gray-200">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th v-for="header in headers" :key="header.key" class="p-2 border-b">{{ header.label }}</th>
          <th class="p-2 border-b">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item[primaryKey]" class="hover:bg-gray-50">
          <td v-for="header in headers" :key="header.key" class="p-2 border-b">
            <template v-if="header.key === 'team_logo'">
              <img :src="item.team_logo ? `/storage/${item.team_logo}` : placeholderImage" class="w-10 h-10 rounded object-cover" />
            </template>
            <template v-else>
              {{ item[header.key] }}
            </template>
          </td>
          <td class="p-2 border-b">
            <button @click="$emit('delete', item[primaryKey])" class="text-red-500 hover:underline">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
defineProps({
  headers: Array,
  items: Array,
  primaryKey: String,
  placeholderImage: {
    type: String,
    default: 'https://via.placeholder.com/48x48?text=No+Logo'
  }
})
defineEmits(['delete'])
</script>
