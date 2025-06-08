<script setup lang="ts">
import { ref, defineProps, defineEmits } from 'vue'
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
  DialogTrigger
} from '@/components/ui/dialog'
import axios from 'axios'

const props = defineProps<{
  team: {
    team_id: number
    team_name: string
  }
}>()

const isOpen = ref(false)

const emit = defineEmits(['deleted'])

const deleteTeamAndPlayers = async () => {
  try {
    // Note we're using the new endpoint
    await axios.delete(`/api/teams/${props.team.team_id}/with-players`);
    emit('deleted');
    isOpen.value = false;
  } catch (error) {
    console.error('Delete failed:', error);
    // Add user feedback here if needed
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
        Are you sure you want to delete <strong>{{ props.team.team_name }}</strong>?
        <br>
        <span class="text-red-500">This will also delete all players in this team!</span>
      </p>

      <DialogFooter>
        <Button variant="outline" @click="isOpen = false">Cancel</Button>
        <Button variant="destructive" @click="deleteTeamAndPlayers">Delete Team and Players</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>