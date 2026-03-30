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
        $response = $this->post('/register', [
            'company_name' => 'Brndini',
            'email' => 'hello@example.com',
            'password' => 'secret123',
            'site_name' => 'Brndini Main Site',
            'domain' => 'brndini.com',
        ]);

        $response->assertRedirect('/dashboard');
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
        $this->post('/register', [
            'company_name' => 'Brndini',
            'email' => 'hello@example.com',
            'password' => 'secret123',
            'site_name' => 'Brndini Main Site',
            'domain' => 'brndini.com',
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
