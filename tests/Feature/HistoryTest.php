<?php

namespace Tests\Feature;

use App\Http\Controllers\HistoryWeatherController;
use App\Models\HistoryWeather;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete()
    {
        $this->withoutExceptionHandling();
        $history = HistoryWeather::factory()->create();
        $response = $this->delete(route('delete', $history->id));
        $response->assertStatus(302);
        $this->assertCount(0, HistoryWeather::all());
    }

    public function test_clear()
    {
        $this->withoutExceptionHandling();
        $history = HistoryWeather::factory()->create();
        $response = $this->get(route('clear'));
        $response->assertStatus(302);
        $this->assertCount(0, HistoryWeather::all());
    }

    public function test_index()
    {
        $this->withoutExceptionHandling();
        $history = HistoryWeather::factory()->create();
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $this->assertCount(1, HistoryWeather::all());
    }

    public function testStoreMethod()
    {
        $data = (object) [
            'coord' => (object) [
                'lat' => 10.1234,
                'lon' => -20.5678,
            ],
            'main' => (object) [
                'temp' => 25.0,
                'feels_like' => 27.0,
                'temp_min' => 23.0,
                'temp_max' => 28.0,
                'pressure' => 1008,
                'humidity' => 70,
            ],
        ];

        $controller = new HistoryWeatherController();

        $controller->store($data, 'TestCity');

        $this->assertCount(1, HistoryWeather::all());
        $this->assertDatabaseHas('history_weathers', [
            'name_city' => 'TestCity',
            'latitude' => 10.1234,
            'longitude' => -20.5678,
            'temp' => 25.0,
            'feels_like' => 27.0,
            'temp_min' => 23.0,
            'temp_max' => 28.0,
            'pressure' => 1008,
            'humidity' => 70,
        ]);
    }
}
