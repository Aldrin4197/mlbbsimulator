<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Create from './Create.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from './DataTable.vue'
import axios from 'axios'

// Breadcrumbs for navigation
const breadcrumbs = [
  { title: 'Players', href: '/players' }
]

// Reactive player list
const players = ref([])

// Fetch players from API
const fetchPlayers = async () => {
  try {
    const response = await axios.get('/api/players')
    players.value = response.data
  } catch (error) {
    console.error('Failed to fetch players:', error)
  }
}

// Fetch on page load
onMounted(() => {
  fetchPlayers()
})

// Handle refresh events
const handleRefresh = () => {
  fetchPlayers()
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Create Player Form -->
      <Create @player-added="fetchPlayers" />

      <!-- Data Table -->
      <div class="max-w-full">
        <div class="overflow-hidden shadow-sm sm:rounded-lg">
          <div class="text-gray-900">
            <div class="flex justify-between items-center mb-6" />
            
            <!-- Listen to refresh events -->
            <DataTable 
              :players="players" 
              @refresh="handleRefresh" 
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>