<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
  DialogTrigger
} from '@/components/ui/dialog'
import axios from 'axios'

interface Team {
  team_id: number
  team_name: string
  short_name: string
  address: string
  team_logo?: string
}

const props = defineProps<{
  team: Team
}>()

const emit = defineEmits(['updated'])

const isOpen = ref(false)
const isLoading = ref(false)
const errors = ref<Record<string, string>>({})
const showDeleteConfirm = ref(false)

const form = ref({
  team_name: '',
  short_name: '',
  address: '',
  team_logo: null as File | null,
  delete_existing_logo: false
})

watch(isOpen, (open) => {
  if (open) {
    form.value = {
      team_name: props.team.team_name,
      short_name: props.team.short_name,
      address: props.team.address,
      team_logo: null,
      delete_existing_logo: false
    }
    errors.value = {}
    showDeleteConfirm.value = false
  }
})

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    form.value.team_logo = target.files[0]
    // Show confirmation if replacing existing logo
    if (props.team.team_logo) {
      showDeleteConfirm.value = true
    }
  }
}

const removeLogo = () => {
  form.value.delete_existing_logo = true
  form.value.team_logo = null
  showDeleteConfirm.value = false
}

const updateTeam = async () => {
  isLoading.value = true
  errors.value = {}

  try {
    const formData = new FormData()
    formData.append('team_name', form.value.team_name)
    formData.append('short_name', form.value.short_name)
    formData.append('address', form.value.address)
    
    if (form.value.team_logo) {
      formData.append('team_logo', form.value.team_logo)
    }
    
    // Add flag to delete existing logo
    if (form.value.delete_existing_logo) {
      formData.append('delete_logo', 'true')
    }

    formData.append('_method', 'PUT')

    const response = await axios.post(`/api/teams/${props.team.team_id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    if (response.status === 200) {
      emit('updated')
      isOpen.value = false
    }
  } catch (error: any) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      console.error('Validation errors:', errors.value)
    } else {
      console.error('Team update failed:', error)
      errors.value.general = error.response?.data?.message || 'Failed to update team'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <Button variant="secondary">Edit</Button>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[425px]">
      <DialogHeader>
        <DialogTitle>Edit Team</DialogTitle>
      </DialogHeader>

      <div class="grid gap-4 py-4">
        <!-- Team Name -->
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="team_name" class="text-right">
            Team Name
          </Label>
          <Input
            id="team_name"
            v-model="form.team_name"
            class="col-span-3"
            :disabled="isLoading"
          />
        </div>

        <!-- Short Name -->
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="short_name" class="text-right">
            Short Name
          </Label>
          <Input
            id="short_name"
            v-model="form.short_name"
            class="col-span-3"
            :disabled="isLoading"
          />
        </div>

        <!-- Address -->
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="address" class="text-right">
            Address
          </Label>
          <Input
            id="address"
            v-model="form.address"
            class="col-span-3"
            :disabled="isLoading"
          />
        </div>

        <!-- Team Logo -->
        <div class="grid grid-cols-4 items-center gap-4">
          <Label class="text-right">
            Team Logo
          </Label>
          <div class="col-span-3 space-y-2">
            <Input
              type="file"
              accept="image/*"
              @change="onFileChange"
              :disabled="isLoading"
            />
            
            <!-- Current Logo Display -->
            <div v-if="team.team_logo && !form.delete_existing_logo" class="mt-2 flex items-center gap-4">
              <img 
                :src="'/storage/' + team.team_logo" 
                alt="Current Logo" 
                class="h-16 w-16 object-contain border rounded"
              />
              <Button 
                type="button" 
                variant="destructive" 
                size="sm"
                @click="removeLogo"
                :disabled="isLoading"
              >
                Remove Logo
              </Button>
            </div>
            
            <!-- Confirmation when replacing logo -->
            <div v-if="showDeleteConfirm" class="p-3 bg-yellow-50 rounded-md text-sm text-yellow-800 mt-2">
              <p>Uploading a new logo will replace the existing one. Continue?</p>
              <div class="flex gap-2 mt-2">
                <Button variant="outline" size="sm" @click="showDeleteConfirm = false">Cancel</Button>
                <Button variant="default" size="sm" @click="showDeleteConfirm = false; form.delete_existing_logo = true">
                  Confirm Replace
                </Button>
              </div>
            </div>
          </div>
        </div>

        <!-- Error Messages -->
        <div v-if="errors.team_name" class="col-span-4 text-sm text-red-500">
          {{ errors.team_name }}
        </div>
        <div v-if="errors.short_name" class="col-span-4 text-sm text-red-500">
          {{ errors.short_name }}
        </div>
        <div v-if="errors.address" class="col-span-4 text-sm text-red-500">
          {{ errors.address }}
        </div>
        <div v-if="errors.team_logo" class="col-span-4 text-sm text-red-500">
          {{ errors.team_logo }}
        </div>
        <div v-if="errors.general" class="col-span-4 text-sm text-red-500">
          {{ errors.general }}
        </div>
      </div>

      <DialogFooter>
        <Button variant="outline" @click="isOpen = false" :disabled="isLoading">
          Cancel
        </Button>
        <Button @click="updateTeam" :disabled="isLoading">
          <span v-if="isLoading">Saving...</span>
          <span v-else>Save Changes</span>
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>