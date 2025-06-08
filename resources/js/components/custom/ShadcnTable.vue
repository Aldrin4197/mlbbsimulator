<script setup lang="ts">
import type {
  ColumnDef,
  SortingState,
  ColumnFiltersState,
  VisibilityState,
  ExpandedState,
} from '@tanstack/vue-table'

import {
  FlexRender,
  getCoreRowModel,
  getExpandedRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  getSortedRowModel,
  useVueTable,
} from '@tanstack/vue-table'

import { h, ref, watch } from 'vue'
import { ChevronDown } from 'lucide-vue-next'

import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Input } from '@/components/ui/input'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

interface Team {
  team_id: number
  team_name: string
  team_logo?: string | null
}

interface Props {
  data: Team[]
  columns?: ColumnDef<Team>[]
  primaryKey: keyof Team
}

const props = withDefaults(defineProps<Props>(), {
  columns: () => [],
})

// Utilities for fallback color
const stringToColor = (str: string) => {
  let hash = 0
  for (let i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash)
  }
  let color = '#'
  for (let i = 0; i < 3; i++) {
    const value = (hash >> (i * 8)) & 0xff
    color += ('00' + value.toString(16)).slice(-2)
  }
  return color
}

const getContrastColor = (hexColor: string) => {
  const r = parseInt(hexColor.substr(1, 2), 16)
  const g = parseInt(hexColor.substr(3, 2), 16)
  const b = parseInt(hexColor.substr(5, 2), 16)
  const brightness = (r * 299 + g * 587 + b * 114) / 1000
  return brightness > 128 ? '#000000' : '#FFFFFF'
}

// Core columns
const defaultColumns: ColumnDef<Team>[] = [
  {
    accessorKey: 'team_logo',
    header: 'Logo',
    cell: ({ row }) => {
      const team = row.original
      const logo = team.team_logo
      const teamName = team.team_name

      return h('div', { class: 'relative h-10 w-10 flex items-center justify-center' }, [
        logo &&
          h('img', {
            src: logo,
            class: 'h-full w-full object-contain absolute rounded-full border',
            alt: `${teamName} logo`,
            onError: (e) => {
              const target = e.target as HTMLImageElement
              target.style.display = 'none'
              target.nextElementSibling?.classList.remove('hidden')
            },
          }),
        h(
          'div',
          {
            class:
              'h-full w-full flex items-center justify-center rounded-full text-xs font-bold absolute',
            style: {
              backgroundColor: stringToColor(teamName),
              color: getContrastColor(stringToColor(teamName)),
            },
          },
          teamName.slice(0, 2).toUpperCase(),
        ),
      ])
    },
  },
  {
    accessorKey: 'team_name',
    header: 'Team Name',
    cell: ({ row }) =>
      h('div', { class: 'font-medium' }, row.original.team_name),
  },
]

const columns = ref<ColumnDef<Team>[]>([...defaultColumns, ...props.columns])

const sorting = ref<SortingState>([])
const columnFilters = ref<ColumnFiltersState>([])
const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})
const expanded = ref<ExpandedState>({})

const table = useVueTable({
  data: props.data,
  columns: columns.value,
  getRowId: (row) => row[props.primaryKey]?.toString() ?? '',
  getCoreRowModel: getCoreRowModel(),
  getPaginationRowModel: getPaginationRowModel(),
  getSortedRowModel: getSortedRowModel(),
  getFilteredRowModel: getFilteredRowModel(),
  getExpandedRowModel: getExpandedRowModel(),
  state: {
    sorting: sorting.value,
    columnFilters: columnFilters.value,
    columnVisibility: columnVisibility.value,
    rowSelection: rowSelection.value,
    expanded: expanded.value,
  },
})

watch(
  () => props.data,
  (newData) => {
    table.options.data = newData
  },
  { deep: true },
)

watch(
  () => props.columns,
  (newColumns) => {
    columns.value = [...defaultColumns, ...newColumns]
  },
)
</script>

<template>
  <div class="w-full space-y-4">
    <div class="flex items-center justify-between gap-2">
      <Input
        class="max-w-sm"
        placeholder="Filter teams..."
        :model-value="table.getColumn('team_name')?.getFilterValue() as string"
        @update:model-value="table.getColumn('team_name')?.setFilterValue($event)"
      />
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="outline" class="ml-auto">
            Columns <ChevronDown class="ml-2 h-4 w-4" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          <DropdownMenuCheckboxItem
            v-for="column in table.getAllColumns().filter(c => c.getCanHide())"
            :key="column.id"
            class="capitalize"
            :checked="column.getIsVisible()"
            @update:checked="(value) => column.toggleVisibility(!!value)"
          >
            {{ column.id.replace('_', ' ') }}
          </DropdownMenuCheckboxItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>

    <div class="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
            <TableHead v-for="header in headerGroup.headers" :key="header.id">
              <FlexRender
                v-if="!header.isPlaceholder"
                :render="header.column.columnDef.header"
                :props="header.getContext()"
              />
            </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <template v-if="table.getRowModel().rows.length">
            <TableRow
              v-for="row in table.getRowModel().rows"
              :key="row.id"
              :data-state="row.getIsSelected() && 'selected'"
            >
              <TableCell
                v-for="cell in row.getVisibleCells()"
                :key="cell.id"
              >
                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
              </TableCell>
            </TableRow>
          </template>
          <template v-else>
            <TableRow>
              <TableCell :colspan="columns.length" class="h-24 text-center">
                No teams found.
              </TableCell>
            </TableRow>
          </template>
        </TableBody>
      </Table>
    </div>

    <div class="flex items-center justify-between px-2">
      <div class="text-sm text-muted-foreground">
        {{ table.getFilteredSelectedRowModel().rows.length }} of
        {{ table.getFilteredRowModel().rows.length }} row(s) selected.
      </div>
      <div class="flex items-center space-x-2">
        <Button
          variant="outline"
          size="sm"
          :disabled="!table.getCanPreviousPage()"
          @click="table.previousPage()"
        >
          Previous
        </Button>
        <Button
          variant="outline"
          size="sm"
          :disabled="!table.getCanNextPage()"
          @click="table.nextPage()"
        >
          Next
        </Button>
      </div>
    </div>
  </div>
</template>
