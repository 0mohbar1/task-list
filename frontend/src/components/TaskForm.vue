<script setup>
const MAX_LENGTH = 100

const title = defineModel({ type: String, required: true })

defineProps({
  busy: { type: Boolean, default: false },
})

defineEmits(['submit'])
</script>

<template>
  <form class="task-form" @submit.prevent="$emit('submit')">
    <input
      v-model="title"
      type="text"
      placeholder="What needs to be done?"
      aria-label="Task title"
    />
    <button type="submit" :disabled="busy">
      {{ busy ? 'Adding…' : 'Add' }}
    </button>
    <small
      class="task-form__counter"
      :class="{ 'task-form__counter--over': title.length > MAX_LENGTH }"
    >
      {{ title.length }} / {{ MAX_LENGTH }}
    </small>
  </form>
</template>