<?php

namespace Tests\Feature;

use App\Mail\ServiceLeadReceived;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ServiceLeadTest extends TestCase
{
    use RefreshDatabase;

    private function registerAndGetUser(): array
    {
        $this->post('/register', [
            'company_name' => 'Test Co',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'site_name' => 'Test Site',
            'domain' => 'testsite.com',
            'accepted_terms' => '1',
            'accepted_privacy' => '1',
            'acknowledged_self_service' => '1',
        ]);

        $user = User::where('email', 'test@example.com')->firstOrFail();
        $site = $user->sites()->firstOrFail();

        return [$user, $site];
    }

    public function test_public_service_lead_form_submits_successfully(): void
    {
        Mail::fake();

        $response = $this->post('/brndini-services/leads', [
            'name' => 'יוסי כהן',
            'email' => 'yossi@example.com',
            'service_type' => 'seo',
            'goal' => 'לשפר את הנוכחות בגוגל',
            'message' => 'אני מחפש שירות SEO מלא לאתר העסקי שלי, כולל מחקר מילות מפתח.',
            'preferred_contact' => 'email',
        ]);

        $response->assertRedirectContains(route('brndini.services'));
        $response->assertSessionHas('status');
    }

    public function test_public_service_lead_sends_email_to_admin(): void
    {
        Mail::fake();

        $this->post('/brndini-services/leads', [
            'name' => 'דנה לוי',
            'email' => 'dana@example.com',
            'service_type' => 'seo',
            'goal' => 'חשיפה אורגנית',
            'message' => 'רוצה לשפר את הדירוג של האתר שלי בתוצאות החיפוש של גוגל.',
            'preferred_contact' => 'email',
        ]);

        Mail::assertSent(ServiceLeadReceived::class, function ($mail) {
            return $mail->hasTo(config('services.a11y_bridge.super_admin_email', 'office@brndini.co.il'))
                && $mail->source === 'public';
        });
    }

    public function test_authenticated_user_can_submit_service_lead(): void
    {
        Mail::fake();

        [$user, $site] = $this->registerAndGetUser();

        $response = $this->actingAs($user)->post('/dashboard/services/leads', [
            'site_id' => $site->id,
            'service_type' => 'hosting',
            'goal' => 'שדרוג תשתית האתר',
            'message' => 'האתר שלי איטי מאוד ואני רוצה לעבור לאחסון מנוהל עם ביצועים טובים יותר.',
            'preferred_contact' => 'email',
        ]);

        $response->assertRedirect(route('dashboard.services', ['site' => $site->id]));
        $response->assertSessionHas('status');
    }

    public function test_dashboard_service_lead_sends_email_to_admin(): void
    {
        Mail::fake();

        [$user, $site] = $this->registerAndGetUser();

        $this->actingAs($user)->post('/dashboard/services/leads', [
            'site_id' => $site->id,
            'service_type' => 'hosting',
            'goal' => 'שדרוג תשתית',
            'message' => 'מחפש פתרון אחסון מתקדם עם אחריות תפעולית מלאה ופחות תחזוקה עצמאית.',
            'preferred_contact' => 'email',
        ]);

        Mail::assertSent(ServiceLeadReceived::class, function ($mail) {
            return $mail->hasTo(config('services.a11y_bridge.super_admin_email', 'office@brndini.co.il'))
                && $mail->source === 'dashboard';
        });
    }

    public function test_public_lead_requires_name_and_email(): void
    {
        $response = $this->post('/brndini-services/leads', [
            'service_type' => 'seo',
            'goal' => 'שיפור SEO',
            'message' => 'אני רוצה לשפר את הנוכחות הדיגיטלית שלי.',
            'preferred_contact' => 'email',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }
}
