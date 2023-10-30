<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetListTaskTest extends TestCase
{
    /** @test */
    public function user_can_get_all_task(): void
    {
        $this->actingAs(User::factory()->create());
        $task = Task::factory()->create();
        $response = $this->get($this->getListTaskRoute());

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('tasks.index');
        $response->assertSee($task->name);
    }

    public function getListTaskRoute()
    {
        return route('tasks.index');
    }
}
