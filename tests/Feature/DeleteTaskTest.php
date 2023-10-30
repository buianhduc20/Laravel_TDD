<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteTaskTest extends TestCase
{
    public function getDeleteTaskRoute($id)
    {
        return route('tasks.destroy', $id);
    }

    /** @test */
    public function authenticated_user_can_delete_task(): void
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->create();
        $response = $this->delete($this->getDeleteTaskRoute($task->id));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $response->assertRedirect(route('tasks.index'));
    }

    /** @test */
    public function unauthenticated_user_can_not_delete_task(): void
    {
        $task = Task::factory()->create();
        $response = $this->delete($this->getDeleteTaskRoute($task->id));
        $response->assertRedirect('/login');
    }

}
