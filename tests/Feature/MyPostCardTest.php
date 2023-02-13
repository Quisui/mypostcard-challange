<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyPostCardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTableCanLoadItems()
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertOk()
            ->assertDontSee('No Data Found')
            ->assertSee('Berlin – Brandenburger Tor - Grüße aus Berlin');
    }
}
