<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Illuminate\Support\Facades\Route;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen(): void
    {

        $user = User::factory()->create();

        \Auth::logout();
        // $route = \Route::getRoutes()->getByName('login');

        $route = Route::getRoutes()->getByName('login');
        // dd($route);
        dd($route->middlevare());
        $response = $this

        ->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',

        ]);

        dd($response->getContent());

        $this->assertAuthenticated();
        $response->assertNoContent();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
