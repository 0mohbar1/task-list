<script setup>
defineProps({
  task: { type: Object, required: true },
  busy: { type: Boolean, default: false },
})

defineEmits(['complete'])
</script>

<template>
  <li class="task" :class="{ 'task--completed': task.completed }">
    <span class="task__title">{{ task.title }}</span>

    <span v-if="task.completed" class="task__status">Completed</span>
    <button
      v-else
      type="button"
      :disabled="busy"
      @click="$emit('complete', task.id)"
    >
      {{ busy ? 'Saving…' : 'Complete' }}
    </button>
  </li>
</template>