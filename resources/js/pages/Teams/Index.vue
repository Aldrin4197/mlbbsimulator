<script setup lang="ts">
import { ref, onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Create from './AddTeam.vue'
import DataTable from './DataTable.vue'
import axios from 'axios'

// Breadcrumbs for navigation
const breadcrumbs = [
  { title: 'Teams', href: '/teams' }
]

// Reactive team list
const teams = ref([])

// Fetch teams from API
const fetchTeams = async () => {
  try {
    const response = await axios.get('/api/teams')
    // Ensure we're properly replacing the array to maintain reactivity
    teams.value = response.data || []
  } catch (error) {
    console.error('Failed to fetch teams:', error)
    teams.value = [] // Reset on error
  }
}

// Fetch on page load
onMounted(() => {
  fetchTeams()
})

// Handle refresh events
const handleRefresh = () => {
  fetchTeams()
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <!-- Create Team Form -->
      <div class="mb-6">
        <!-- Listen for 'created' event emitted from AddTeam.vue -->
        <Create @created="handleRefresh" />
      </div>

      <!-- Data Table -->
      <div class="max-w-full">
        <div class="overflow-hidden shadow-sm sm:rounded-lg">
          <div class="text-gray-900">
            <div class="flex justify-between items-center mb-6">
              <!-- Add buttons or title here if needed -->
            </div>

            <!-- Pass teams to DataTable and listen to refresh -->
            <DataTable 
              :teams="teams" 
              @refresh="handleRefresh" 
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
