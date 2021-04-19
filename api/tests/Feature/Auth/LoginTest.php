<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions; //не записываем данные в БД

    //тест на открытие главной страницы сайта
    public function testForm()
    {
        $response = $this->get('/api/v1/login');

        $response
            ->assertStatus(200) //страница открылась
            ->assertSee('Вход на сайт'); //мы увидели данную строку на экране, заменить на Вход на сайт
    }

    //тест передачи Post-запроса от пользователя
    public function testLogin()
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'best2@gmail.com',
            'password' => 'bestbar0000',
        ]);

        $response
            ->assertStatus(200);
    }

    //тест передачи Post-запроса от пользователя, отправляем пустые, чтобы вышла ошибка с кодом 302
    public function testErrors()
    {
        $response = $this->post('/api/v1/login', [
            'email' => '',
            'password' => '',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'password']);
    }
}
