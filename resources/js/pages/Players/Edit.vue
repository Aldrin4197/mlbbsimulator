<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter, DialogTrigger } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import axios from 'axios'

const props = defineProps<{
  player: {
    player_id: number
    player_ign: string
    player_role: string
    team_id: number
    status: string
  }
}>()

const isOpen = ref(false)

const form = ref({
  player_ign: '',
  player_role: '',
  team_id: '',
  status: 'starter',
})

// Prefill the form when modal opens
watch(isOpen, (open) => {
  if (open) {
    form.value = {
      player_ign: props.player.player_ign,
      player_role: props.player.player_role,
      team_id: String(props.player.team_id),
      status: props.player.status,
    }
  }
})

const emit = defineEmits(['updated']) // <-- define event

const updatePlayer = async () => {
  try {
    await axios.put(`/api/players/${props.player.player_id}`, form.value)
    emit('updated') // <-- notify parent
    isOpen.value = false
  } catch (error) {
    console.error('Update failed:', error)
  }
}
</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <Button variant="secondary">Edit</Button>
    </DialogTrigger>

    <DialogContent>
      <DialogHeader>
        <DialogTitle>Edit Player</DialogTitle>
      </DialogHeader>

      <div class="space-y-4">
        <input v-model="form.player_ign" placeholder="IGN" class="w-full border rounded p-2" />
        <select v-model="form.player_role" placeholder="Role" class="w-full border rounded p-2">
          <option value="starter">Jungle</option>
          <option value="bench">Bench</option>
        </select>
        <input v-model="form.team_id" placeholder="Team ID" class="w-full border rounded p-2" />
        <select v-model="form.status" class="w-full border rounded p-2">
          <option value="starter">Starter</option>
          <option value="bench">Bench</option>
        </select>
      </div>

      <DialogFooter>
        <Button variant="outline" @click="isOpen = false">Cancel</Button>
        <Button @click="updatePlayer">Save</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
