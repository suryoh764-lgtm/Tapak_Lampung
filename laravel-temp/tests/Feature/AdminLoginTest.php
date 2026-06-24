<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    /**
     * Test admin login and redirection.
     */
    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        // Ensure the admin user exists with the password 'password123'
        $user = User::updateOrCreate(
            ['email' => 'admin@tapaklampung.id'],
            [
                'name' => 'Admin Tapak Lampung',
                'email' => 'admin@tapaklampung.id',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'role' => 'admin',
            ]
        );

        $response = $this->post('/login', [
            'email' => 'admin@tapaklampung.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');

        $authenticatedResponse = $this->actingAs($user)->get('/admin');
        $authenticatedResponse->assertStatus(200);
    }
}
