<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class JwtDebugTest extends TestCase
{
    use RefreshDatabase;

    public function test_jwt_auth_expenses_endpoint()
    {
        // 1. Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Generate Token
        $token = Auth::guard('api')->login($user);

        echo "\nGenerated Token: " . substr($token, 0, 10) . "...\n";

        // 3. Make Request with Token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/expenses', [
                    'title' => 'Test Expense',
                    'amount' => 100,
                    'category' => 'Food',
                    'expense_date' => '2023-10-01',
                ]);

        // 4. Debug Output
        if ($response->status() === 500) {
            echo "\n--- 500 Error Exception ---\n";
            echo $response->exception->getMessage() . "\n";
            echo $response->exception->getTraceAsString();
        }

        $response->assertStatus(201);
    }
}
