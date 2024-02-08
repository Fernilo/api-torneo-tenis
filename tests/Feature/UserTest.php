<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
	use RefreshDatabase;
	public function test_register_user()
	{
		// Crea un usuario de prueba
		$usuario =  User::factory()->make()->toArray();
		//Registro incorrecto
		$response = $this->post(route('register'), [
			'name' => '',
			'email' => $usuario['email'],
			'password' => 'password',
		]);

		$response->assertStatus(400);

		$response = $this->post(route('register'), [
			'name' => $usuario['name'],
			'email' => $usuario['email'],
			'password' => 'password',
		]);

		$response->assertStatus(200);

		//verifica si el usuario se creó correctamente en la base de datos
		$this->assertDatabaseHas('users', [
            'email' => $usuario['email'],
        ]);

	}
}
