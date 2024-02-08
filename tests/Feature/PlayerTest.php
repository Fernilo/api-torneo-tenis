<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_player()
    {
        $this->withoutMiddleware(Authenticate::class);
        // Crea un usuario de prueba
		$player =  Player::factory()->make()->toArray();
		//Registro incorrecto
		$response = $this->post('/api/players', [
			'name' => $player['name'],
			'skill' => $player['skill'],
			'good_look' => $player['good_look'],
            'type' => 1,
		]);

		$response->assertStatus(201);

        $this->assertDatabaseHas('players', [
            'name' => $player['name'],
        ]);

        $player = Player::where('name',$player['name'])->first();

        $response = $this->delete("players/".$player->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('players', [$player->id]);

        $this->withMiddleware(Authenticate::class);
    }
}
