<?php

namespace Tests\Feature;

use App\Models\Site;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndWidgetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register_and_receive_a_site_key(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $response = $this->post('/register', [
            'company_name' => 'Brndini',
            'email' => 'hello@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'site_name' => 'Brndini Main Site',
            'domain' => 'brndini.com',
            'accepted_terms' => '1',
            'accepted_privacy' => '1',
            'acknowledged_self_service' => '1',
        ]);

        $response->assertRedirectContains('/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'hello@example.com',
            'contact_email' => 'hello@example.com',
        ]);

        $this->assertDatabaseHas('sites', [
            'site_name' => 'Brndini Main Site',
            'domain' => 'https://brndini.com',
        ]);
    }

    public function test_the_public_widget_endpoint_returns_widget_settings(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $this->post('/register', [
            'company_name' => 'Brndini',
            'email' => 'hello@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'site_name' => 'Brndini Main Site',
            'domain' => 'brndini.com',
            'accepted_terms' => '1',
            'accepted_privacy' => '1',
            'acknowledged_self_service' => '1',
        ]);

        $site = Site::firstOrFail();

        $response = $this->getJson('/api/public/widget-config/' . $site->public_key);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.siteName', 'Brndini Main Site')
            ->assertJsonPath('data.widget.label', 'נגישות');
    }
}
