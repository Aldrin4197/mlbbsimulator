<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Team Management</h1>

    <!-- Add Team Form -->
    <form @submit.prevent="createTeam" class="mb-6 space-y-4">
      <input
        v-model="form.team_name"
        placeholder="Team Name"
        class="border p-2 w-full"
        required
        :disabled="loading"
      />
      <input
        ref="fileInput"
        type="file"
        accept="image/*"
        @change="handleFileUpload"
        class="border p-2 w-full"
        :disabled="loading"
      />
      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded disabled:opacity-50"
        :disabled="loading"
      >
        {{ loading ? 'Saving...' : 'Add Team' }}
      </button>
    </form>

    <!-- Teams List -->
    <div v-if="teams.length" class="space-y-4">
      <div
        v-for="team in teams"
        :key="team.team_id"
        class="border p-4 rounded shadow flex justify-between items-center"
      >
        <div class="flex items-center space-x-4">
          <img
            :src="team.team_logo ? `/storage/${team.team_logo}` : placeholderImage"
            alt="logo"
            class="w-12 h-12 object-cover rounded"
          />
          <span class="font-semibold">{{ team.team_name }}</span>
        </div>
        <button
          @click="deleteTeam(team.team_id)"
          class="text-red-500 hover:underline"
          :disabled="loading"
        >
          Delete
        </button>
      </div>
    </div>
    <div v-else>No teams added yet.</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const teams = ref([])
const form = ref({
  team_name: '',
  team_logo: null,
})
const loading = ref(false)
const fileInput = ref(null)
const placeholderImage = 'https://via.placeholder.com/48x48?text=No+Logo'

const fetchTeams = async () => {
  try {
    const response = await axios.get('/api/teams')
    teams.value = response.data
  } catch (error) {
    console.error('Error fetching teams:', error)
  }
}

const handleFileUpload = (event) => {
  form.value.team_logo = event.target.files[0]
}

const createTeam = async () => {
  if (!form.value.team_name.trim()) {
    alert('Team name is required')
    return
  }

  loading.value = true
  try {
    const formData = new FormData()
    formData.append('team_name', form.value.team_name)
    if (form.value.team_logo) {
      formData.append('team_logo', form.value.team_logo)
    }

    await axios.post('/api/teams', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    // Reset form
    form.value.team_name = ''
    form.value.team_logo = null
    if (fileInput.value) fileInput.value.value = null

    await fetchTeams()
  } catch (error) {
    console.error('Error creating team:', error)
    alert('Failed to create team. See console for details.')
  } finally {
    loading.value = false
  }
}

const deleteTeam = async (id) => {
  if (!confirm('Are you sure you want to delete this team?')) return
  loading.value = true
  try {
    await axios.delete(`/api/teams/${id}`)
    await fetchTeams()
  } catch (error) {
    console.error('Error deleting team:', error)
    alert('Failed to delete team. See console for details.')
  } finally {
    loading.value = false
  }
}

onMounted(fetchTeams)
</script>
