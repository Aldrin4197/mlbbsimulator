<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

const isOpen = ref(false)

const teams = ref<Array<{ team_id: number; team_name: string }>>([])

const form = useForm({
  player_ign: '',
  player_role: '',
  player_photo: null as File | null,
  team_id: '',
  status: 'bench',
})

const fetchTeams = async () => {
  try {
    const response = await axios.get('/api/teams')
    teams.value = response.data
  } catch (error) {
    console.error('Failed to fetch teams:', error)
  }
}

onMounted(() => {
  fetchTeams()
})

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    form.player_photo = target.files[0]
  }
}

const emit = defineEmits(['player-added'])

const submit = async () => {
  const formData = new FormData()
  formData.append('player_ign', form.player_ign)
  formData.append('player_role', form.player_role)
  formData.append('team_id', form.team_id)
  formData.append('status', form.status)
  if (form.player_photo) {
    formData.append('player_photo', form.player_photo)
  }

  try {
    const response = await axios.post('/api/players', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    // Optional: Validate success
    if (response.status === 201) {
      alert('Player created successfully')
      emit('player-added') // 👈 Make sure this is called
      form.reset()          // ✅ Call only if form supports reset()
      isOpen.value = false
    }
  } catch (error) {
    console.error('Error creating player:', error)
    alert('Failed to create player')
  }
}


</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <Button variant="outline">Add New Player</Button>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Create New Player</DialogTitle>
        <DialogDescription>
          Fill out the form to add a new player. Click save when you're done.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="grid gap-4 py-4">
        <div>
          <Label for="player_ign">Player IGN</Label>
          <Input id="player_ign" v-model="form.player_ign" required />
        </div>

        <div>
          <Label for="player_role">Player Role</Label>
          <Select v-model="form.player_role" required>
            <SelectTrigger>
              <SelectValue placeholder="Select a role" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="Jungle">Jungle</SelectItem>
              <SelectItem value="Gold Lane">Gold Lane</SelectItem>
              <SelectItem value="Mid Lane">Mid Lane</SelectItem>
              <SelectItem value="EXP Lane">EXP Lane</SelectItem>
              <SelectItem value="Roam">Roam</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div>
          <Label for="team_id">Team</Label>
          <Select v-model="form.team_id" required>
            <SelectTrigger>
              <SelectValue placeholder="Select a team" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="team in teams"
                :key="team.team_id"
                :value="team.team_id.toString()"
              >
                {{ team.team_name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div>
          <Label for="player_photo">Player Photo</Label>
          <input
            type="file"
            id="player_photo"
            accept="image/*"
            @change="onFileChange"
            required
          />
        </div>

        <div>
          <Label for="status">Status</Label>
          <Select v-model="form.status" required>
            <SelectTrigger>
              <SelectValue placeholder="Select status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="bench">Bench</SelectItem>
              <SelectItem value="active">Active</SelectItem>
              <SelectItem value="inactive">Inactive</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div class="flex justify-end gap-2">
          <Button
            type="button"
            variant="outline"
            @click="isOpen = false"
          >
            Cancel
          </Button>
          <Button type="submit" :disabled="form.processing">
            <span v-if="form.processing">Saving...</span>
            <span v-else>Save</span>
          </Button>
        </div>
      </form>
    </DialogContent>
  </Dialog>
</template>
