<?php

namespace Tests\Feature\Auth;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use DatabaseTransactions; //не записываем данные в БД

    public function testForm()
    {
        $response = $this->get('/api/v1/register');

        $response
            ->assertStatus(200)
            ->assertSee('Регистрация');
    }

    public function testErrors()
    {
        $response = $this->post('/api/v1/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function testSuccess()
    {
        $user = factory(User::class)->make();

        $response = $this->post('/api/v1/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response
            ->assertStatus(200);
    }
}
