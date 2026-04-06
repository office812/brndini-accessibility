<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DashboardFlowTest extends TestCase
{
    use RefreshDatabase;

    private function register(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post('/register', array_merge([
            'company_name' => 'My Company',
            'email' => 'user@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'site_name' => 'My Website',
            'domain' => 'mywebsite.com',
            'accepted_terms' => '1',
            'accepted_privacy' => '1',
            'acknowledged_self_service' => '1',
        ], $overrides));
    }

    public function test_registration_sends_welcome_email(): void
    {
        Mail::fake();

        $this->register();

        Mail::assertSent(WelcomeMail::class, function ($mail) {
            return $mail->hasTo('user@example.com');
        });
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
    }

    public function test_authenticated_user_can_access_compliance_page(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();

        $response = $this->actingAs($user)->get('/dashboard/compliance');

        $response->assertOk();
    }

    public function test_authenticated_user_can_access_account_page(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();

        $response = $this->actingAs($user)->get('/dashboard/account');

        $response->assertOk();
    }

    public function test_authenticated_user_can_access_install_page(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();

        $response = $this->actingAs($user)->get('/dashboard/install');

        $response->assertOk();
    }

    public function test_authenticated_user_can_access_support_page(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();

        $response = $this->actingAs($user)->get('/dashboard/support');

        $response->assertOk();
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        // The app redirects unauthenticated users to home (see Authenticate middleware)
        $response->assertRedirect(route('home'));
    }

    public function test_user_can_update_widget_settings(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();
        $site = $user->sites()->firstOrFail();

        $response = $this->actingAs($user)->post('/dashboard', [
            'site_id' => $site->id,
            'company_name' => 'My Company',
            'contact_email' => 'user@example.com',
            'site_name' => 'My Website',
            'domain' => 'mywebsite.com',
            'service_mode' => 'audit_only',
            'widget' => [
                'position' => 'bottom-left',
                'color' => '#ff0000',
                'size' => 'compact',
                'label' => 'נגישות',
                'language' => 'he',
                'buttonMode' => 'icon-label',
                'buttonStyle' => 'solid',
                'icon' => 'figure',
                'preset' => 'classic',
                'panelLayout' => 'stacked',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_user_can_run_audit(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();
        $site = $user->sites()->firstOrFail();

        $response = $this->actingAs($user)->post('/dashboard/compliance/audit', [
            'site_id' => $site->id,
        ]);

        $response->assertRedirect(route('dashboard.compliance', ['site' => $site->id]));
        $response->assertSessionHas('status');
    }

    public function test_user_can_update_statement_builder(): void
    {
        $this->register();
        $user = User::where('email', 'user@example.com')->firstOrFail();
        $site = $user->sites()->firstOrFail();

        $response = $this->actingAs($user)->post('/dashboard/compliance/statement', [
            'site_id' => $site->id,
            'statement' => [
                'organization_name' => 'My Company Ltd',
                'organization_type' => 'business',
                'service_scope' => 'website_only',
                'contact_email' => 'contact@mycompany.com',
                'response_time' => '5_business_days',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_widget_config_endpoint_returns_correct_data(): void
    {
        $this->register();
        $site = Site::firstOrFail();

        $response = $this->getJson('/api/public/widget-config/' . $site->public_key);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.siteName', 'My Website');
    }

    public function test_widget_seen_endpoint_tracks_installation(): void
    {
        $this->register();
        $site = Site::firstOrFail();

        $response = $this->postJson('/api/public/widget-seen/' . $site->public_key, [
            'pageUrl' => 'https://mywebsite.com/about',
        ]);

        $response->assertOk();
    }
}
