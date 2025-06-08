<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue'
import { Button } from '@/components/ui/button'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter, DialogTrigger } from '@/components/ui/dialog'
import axios from 'axios'

const props = defineProps<{
  player: {
    player_id: number
    player_ign: string
  }
}>()



const isOpen = ref(false)

const emit = defineEmits(['deleted']) // <-- define event

const deletePlayer = async () => {
  try {
    await axios.delete(`/api/players/${props.player.player_id}`)
    emit('deleted') // <-- notify parent
    isOpen.value = false
  } catch (error) {
    console.error('Delete failed:', error)
  }
}

</script>

<template>
  <Dialog v-model:open="isOpen">
    <DialogTrigger as-child>
      <Button variant="destructive">Delete</Button>
    </DialogTrigger>

    <DialogContent>
      <DialogHeader>
        <DialogTitle>Confirm Delete</DialogTitle>
      </DialogHeader>
      <p class="text-sm text-gray-700">
        Are you sure you want to delete <strong>{{ props.player.player_ign }}</strong>?
      </p>

      <DialogFooter>
        <Button variant="outline" @click="isOpen = false">Cancel</Button>
        <Button variant="destructive" @click="deletePlayer">Delete</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
