<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateNewTaskTest extends TestCase
{
    /** @test */
    public function authenticated_user_can_new_task(): void
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->make()->toArray();
        $response = $this->post($this->getCreateTaskRoute(), $task);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test */
    public function unauthenticated_user_can_not_create_task()
    {
        $task = Task::factory()->make()->toArray();
        $response = $this->post($this->getCreateTaskRoute(), $task);
        $response->assertRedirect('/login');
    }
    /** @test */
    public function authenticated_user_can_not_create_task_if_name_field_is_null()
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->make(['name' => null])->toArray();
        $response = $this->post($this->getCreateTaskRoute(), $task);
        $response->assertSessionHasErrors(['name']);
    }

    public function getCreateTaskRoute()
    {
        return route('tasks.store');
    }
}
