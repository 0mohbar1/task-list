<script setup>
import { computed, onMounted, ref } from 'vue'
import { completeTask, createTask, getTasks } from './api/tasks'
import TaskForm from './components/TaskForm.vue'
import TaskItem from './components/TaskItem.vue'

const tasks = ref([])
const newTitle = ref('')
const error = ref('')
const loading = ref(true)
const adding = ref(false)
const completingId = ref(null)
const summary = computed(() => {
  const total = tasks.value.length
  const completed = tasks.value.filter((task) => task.completed).length

  return `${total} ${total === 1 ? 'task' : 'tasks'} · ${completed} completed`
})

async function loadTasks() {
  loading.value = true
  error.value = ''

  try {
    tasks.value = await getTasks()
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function addTask() {
  adding.value = true
  error.value = ''

  try {
    const task = await createTask(newTitle.value)
    tasks.value.unshift(task)
    newTitle.value = ''
  } catch (e) {
    error.value = e.message
  } finally {
    adding.value = false
  }
}

async function markCompleted(id) {
  completingId.value = id
  error.value = ''

  try {
    const updated = await completeTask(id)
    const index = tasks.value.findIndex((task) => task.id === id)
    tasks.value[index] = updated
  } catch (e) {
    error.value = e.message
  } finally {
    completingId.value = null
  }
}

onMounted(loadTasks)
</script>

<template>
  <main class="app">
    <header class="app__header">
  <h1 class="app__title">Tasks<span class="app__semicolon">;</span></h1>
  <p v-if="!loading" class="app__summary">{{ summary }}</p>
</header>

    <TaskForm v-model="newTitle" :busy="adding" @submit="addTask" />

    <p v-if="error" class="error" role="alert">{{ error }}</p>

    <p v-if="loading" class="hint">Loading tasks…</p>
    <p v-else-if="tasks.length === 0 && !error" class="hint">
      No tasks yet. Add your first one above.
    </p>
    <ul v-else class="task-list">
      <TaskItem
        v-for="task in tasks"
        :key="task.id"
        :task="task"
        :busy="completingId === task.id"
        @complete="markCompleted"
      />
    </ul>
  </main>
</template>