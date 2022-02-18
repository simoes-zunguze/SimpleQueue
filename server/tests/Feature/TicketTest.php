<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class TicketTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();

        $this->seed();
        Sanctum::actingAs(
            User::find(1),
            ['*']
        );
    }

    public function testIfCanNotCallTicketOnEmptyQueue(){
        $response = $this->patchJson('api/call-ticket/1');
        $response->assertStatus(422);
    }

    public function testIfCanPutAbsentBeforeCall(){
        $response = $this->patchJson('api/absent-ticket/1');
        $response->assertStatus(422);
    }

    public function testIfCanNotCloseBeforeCall(){
        $response = $this->patchJson('api/close-ticket/1');
        $response->assertStatus(422);
    }

    public function testIfCanNotRedirectBeforeCall(){
        $response = $this->patchJson('api/redirect-ticket/1');
        $response->assertStatus(422);
    }
    public function testIfCanNotPostponeBeforeCall(){
        $response = $this->patchJson('api/postpone-ticket/1');
        $response->assertStatus(422);
    }

    public function testIfCanGetNewTicket(){
        $response = $this->post('/api/get-ticket/1');
        $response->assertStatus(201);
    }

    public function testIfCanCallTicket(){
        $response = $this->post('/api/get-ticket/1');
        $response = $this->patch('/api/call-ticket/1');
        $response->assertStatus(200);
    }

    public function testIfCanAbsnetTicket(){
        $response = $this->post('/api/get-ticket/1');
        $response = $this->patch('/api/call-ticket/1');
        $response = $this->patch('/api/absent-ticket/1');
        $response->assertStatus(200);
        // dd($response->getContent());
    }


    public function testIfCanCloseTicket(){
        $response = $this->post('/api/get-ticket/1');
        $response = $this->patch('/api/call-ticket/1');
        $response = $this->patch('/api/close-ticket/1');
        $response->assertStatus(200);
    }

    public function testIfCanPostponeTicket(){
        $response = $this->post('/api/get-ticket/1');
        $response = $this->patch('/api/call-ticket/1');
        $response = $this->patch('/api/postpone-ticket/1', ['minutes' => 10]);
        $response->assertStatus(200);
    }

    public function testIfCanRediectTicket(){
        $response = $this->post('/api/get-ticket/1');
        $response = $this->patch('/api/call-ticket/1');
        $response = $this->patch('/api/redirect-ticket/1', ['redirect_to_queue' => 2]);
        $response->assertStatus(200);
    }
}
