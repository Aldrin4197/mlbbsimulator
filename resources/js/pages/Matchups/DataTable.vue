<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import EditTeam from './Edit.vue'
import DeleteTeam from './Delete.vue'

interface Team {
  team_id: number
  team_name: string
  short_name: string
  address: string
  team_logo: string
  created_at: string
  updated_at: string
}

const props = defineProps<{ teams: Team[] }>()
const emit = defineEmits(['refresh'])

const handleRefresh = () => {
  emit('refresh')
}

const handleImageError = (e: Event) => {
  const img = e.target as HTMLImageElement
  img.src = '/placeholder-team.png' // Make sure this image exists in your public folder
  img.onerror = null // Prevent infinite loop if placeholder also fails
}
</script>

<template>
  <div class="rounded-md border text-white">
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead class="w-[100px]">Logo</TableHead>
          <TableHead>Team Name</TableHead>
          <TableHead>Short Name</TableHead>
          <TableHead>Address</TableHead>
          <TableHead>Created At</TableHead>
          <TableHead class="text-right">Actions</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="team in teams" :key="team.team_id">
          <TableCell>
            <div class="flex items-center">
              <img
                v-if="team.team_logo"
                :src="'/storage/' + team.team_logo"
                alt="Team Logo"
                class="h-10 w-10 rounded-full object-cover"
                @error="handleImageError"
              />
              <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                <span class="text-xs text-gray-500">No Logo</span>
              </div>
            </div>
          </TableCell>
          <TableCell class="font-medium">{{ team.team_name }}</TableCell>
          <TableCell>{{ team.short_name }}</TableCell>
          <TableCell>{{ team.address }}</TableCell>
          <TableCell>{{ new Date(team.created_at).toLocaleDateString() }}</TableCell>
          <TableCell class="text-right">
            <div class="flex gap-2 justify-end">
              <EditTeam :team="team" @updated="handleRefresh" />
              <DeleteTeam :team="team" @deleted="handleRefresh" />
            </div>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>
  </div>
</template>