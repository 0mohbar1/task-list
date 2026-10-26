async function request(url, options = {}) {
  let response

  try {
    response = await fetch(url, {
      ...options,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
    })
  } catch {
    throw new Error('Could not reach the server. Please try again.')
  }

  const body = await response.json().catch(() => null)

  if (!response.ok) {
    throw new Error(body?.message ?? 'Something went wrong. Please try again.')
  }

  return body.data
}

export function getTasks() {
  return request('/tasks')
}

export function createTask(title) {
  return request('/tasks', {
    method: 'POST',
    body: JSON.stringify({ title }),
  })
}

export function completeTask(id) {
  return request(`/tasks/${id}`, { method: 'PATCH' })
}