<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected $seed = true;

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
    }

    #[DataProvider('provideInvalidData')]
    #[Test]
    public function checkInvalidLogin($email, $password)
    {
        $response = $this->post('/api/login', ['email' => $email, 'password' => $password]);

        $response->assertStatus(401);
    }


    #[DataProvider('provideValidData')]
    #[Test]
    public function checkValidLogin($email, $password)
    {
        $response = $this->post('/api/login', ['email' => $email, 'password' => $password]);

        $response->assertStatus(200);
    }

    public static function provideInvalidData(): array
    {
        return [[
            'email' => fake()->email,
            'password' => '12345678'
        ]];
    }

    public static function provideValidData(): array
    {
        return [[
            'email' => 'test@example.com',
            'password' => 'password'
        ]];
    }
}
