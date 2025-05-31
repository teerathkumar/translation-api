<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Translation;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

class TranslationPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_performance()
    {
        Sanctum::actingAs(User::factory()->create());
        Translation::factory()->count(10000)->create(['locale' => 'en']);

        $start = microtime(true);
        $response = $this->getJson('/api/translations/export/en');
        $end = microtime(true);

        $response->assertStatus(200);
        $this->assertLessThan(0.5, $end - $start, "Export endpoint took too long");
    }
}
