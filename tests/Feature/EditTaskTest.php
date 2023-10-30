<?php

namespace Tests\Feature;


use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class EditTaskTest extends TestCase
{
    /** @test */
    public function authenticated_user_can_edit_task(): void
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->create();
        $response = $this->put($this->getEditTaskRoute($task->id), $task->toArray());

        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('tasks', $task->toArray());
        $response->assertRedirect(route('tasks.index'));
    }

    /** @test */
    public function unauthenticated_user_can_not_edit_task(): void
    {
        $task = Task::factory()->create();
        $response = $this->put($this->getEditTaskRoute($task->id), $task->toArray());

        $response->assertRedirect('/login');
    }

    /** @test  */
    public function authenticated_user_can_not_create_task_if_name_field_is_null(): void
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->create();
        $data = [
            'name' => null,
            'content' => $task->content
        ];
        $response = $this->put($this->getEditTaskRoute($task->id), $data);
        $response->assertSessionHasErrors(['name']);
    }

    public function getEditTaskRoute($id)
    {
        return route('tasks.update', $id);
    }
}
