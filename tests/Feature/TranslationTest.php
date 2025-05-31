<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Translation;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_translation()
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson('/api/translations', [
            'locale' => 'en',
            'key' => 'greeting',
            'content' => 'Hello',
            'tag' => 'web'
        ]);

        $response->assertStatus(201)->assertJsonFragment(['key' => 'greeting']);
    }
}
