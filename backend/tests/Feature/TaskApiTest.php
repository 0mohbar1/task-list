<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_task(): void
    {
        $this->postJson('/tasks', ['title' => 'Buy milk'])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Buy milk')
            ->assertJsonPath('data.completed', false);

        $this->assertDatabaseHas('tasks', ['title' => 'Buy milk']);
    }

    public function test_it_rejects_invalid_titles(): void
    {
        $invalidTitles = ['', '   ', str_repeat('a', 101)];

        foreach ($invalidTitles as $title) {
            $this->postJson('/tasks', ['title' => $title])
                ->assertUnprocessable()
                ->assertJsonValidationErrors('title');
        }

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_it_accepts_a_title_of_exactly_100_characters(): void
    {
        $this->postJson('/tasks', ['title' => str_repeat('a', 100)])
            ->assertCreated();
    }

    public function test_it_lists_tasks_newest_first(): void
    {
        $older = Task::create(['title' => 'Older']);
        $newer = Task::create(['title' => 'Newer']);

        $this->getJson('/tasks')
            ->assertOk()
            ->assertJsonPath('data.0.id', $newer->id)
            ->assertJsonPath('data.1.id', $older->id);
    }

    public function test_it_marks_a_task_as_completed(): void
    {
        $task = Task::create(['title' => 'Buy milk']);

        $this->patchJson("/tasks/{$task->id}")
            ->assertOk()
            ->assertJsonPath('data.completed', true);

        $this->assertTrue($task->fresh()->completed);
    }

    public function test_it_returns_404_for_a_missing_task(): void
    {
        $this->patchJson('/tasks/999')
            ->assertNotFound()
            ->assertExactJson(['message' => 'Task not found.']);
    }
}
