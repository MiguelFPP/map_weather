<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HistoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_delete_history()
    {
        /* with name route */
        $response = $this->delete(route('delete', ['id' => 1]));
        $response->assertStatus(302);
    }
}
