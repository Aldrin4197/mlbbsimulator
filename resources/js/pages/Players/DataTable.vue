<script setup lang="ts">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Edit from './Edit.vue';
import Delete from './Delete.vue';

interface Player {
  player_id: number;
  player_ign: string;
  player_role: string;
  player_photo: string;
  team_id: number;
  status: string;
  created_at: string;
  updated_at: string;
  team: {
    team_name: string;
  };
}

const props = defineProps<{ players: Player[] }>();
const emit = defineEmits(['refresh']);

const statusVariant = (status: string) => {
  const variantMap: Record<string, string> = {
    active: 'bg-green-100 text-green-800',
    bench: 'bg-gray-100 text-gray-800',
    injured: 'bg-red-100 text-red-800',
  };
  return variantMap[status] || 'bg-gray-100 text-gray-800';
};

const handleRefresh = () => {
  emit('refresh');
};
</script>

<template>
  <div class="rounded-md border text-white">
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>Player IGN</TableHead>
          <TableHead>Role</TableHead>
          <TableHead>Photo</TableHead>
          <TableHead>Team</TableHead>
          <TableHead>Status</TableHead>
          <TableHead>Created At</TableHead>
          <TableHead class="text-right">Actions</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="player in players" :key="player.player_id">
          <TableCell class="font-medium">{{ player.player_ign }}</TableCell>
          <TableCell>{{ player.player_role }}</TableCell>
          <TableCell>
            <img
              :src="player.player_photo"
              alt="Player Photo"
              class="h-10 w-10 rounded-full object-cover"
            />
          </TableCell>
          <TableCell>{{ player.team.team_name }}</TableCell>
          <TableCell>
            <span
              class="rounded-full px-2 py-1 text-xs font-medium"
              :class="statusVariant(player.status)"
            >
              {{ player.status }}
            </span>
          </TableCell>
          <TableCell>{{ new Date(player.created_at).toLocaleDateString() }}</TableCell>
          <TableCell class="text-right flex gap-2 justify-end items-center">
            <Edit :player="player" @updated="handleRefresh" />
            <Delete :player="player" @deleted="handleRefresh" />
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>
  </div>
</template>