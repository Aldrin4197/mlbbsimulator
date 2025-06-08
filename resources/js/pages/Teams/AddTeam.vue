<script setup lang="ts">
import { ref, defineEmits } from 'vue'
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

const isOpen = ref(false)
const form = useForm({
  team_name: '',
  short_name: '',
  address: '',
  team_logo: null as File | null,
})

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    form.team_logo = target.files[0]
  }
}

const emit = defineEmits(['team-added'])

const submit = async () => {
  const formData = new FormData()
  formData.append('team_name', form.team_name)
  formData.append('short_name', form.short_name)
  formData.append('address', form.address)
  if (form.team_logo) {
    formData.append('team_logo', form.team_logo)
  }

  try {
    const response = await axios.post('/api/teams', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })

    if (response.status === 201) {
      emit('created', response.data) // Pass the new team data
      form.reset()
      isOpen.value = false
    }
  } catch (error) {
    console.error('Error creating team:', error)
    alert('Failed to create team: ' + error.response?.data?.message || error.message)
  }
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <Button variant="outline">Add New Team</Button>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Create New Team</DialogTitle>
        <DialogDescription>
          Fill out the form to register a new team.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="submit" enctype="multipart/form-data" class="grid gap-4 py-4">
        <div>
          <Label for="team_name">Team Name</Label>
          <Input id="team_name" v-model="form.team_name" required />
        </div>

        <div>
          <Label for="short_name">Short Name</Label>
          <Input id="short_name" v-model="form.short_name" required />
        </div>

        <div>
          <Label for="address">Address</Label>
          <Input id="address" v-model="form.address" />
        </div>

        <div>
          <Label for="team_logo">Team Logo</Label>
          <input
            type="file"
            id="team_logo"
            accept="image/*"
            @change="onFileChange"
          />
        </div>

        <div class="flex justify-end gap-2">
          <Button
            type="button"
            variant="outline"
            @click="isOpen = false"
          >
            Cancel
          </Button>
          <Button type="submit">
            Save
          </Button>
        </div>
      </form>
    </DialogContent>
  </Dialog>
</template>
