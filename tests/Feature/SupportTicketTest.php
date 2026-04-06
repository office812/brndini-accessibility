<?php

namespace Tests\Feature;

use App\Mail\SupportTicketCreated;
use App\Mail\SupportTicketResponded;
use App\Models\Site;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SupportTicketTest extends TestCase
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

    public function test_authenticated_user_can_create_support_ticket(): void
    {
        Mail::fake();

        [$user, $site] = $this->registerAndGetUser();

        $response = $this->actingAs($user)->post('/dashboard/support/tickets', [
            'site_id' => $site->id,
            'category' => 'technical',
            'priority' => 'normal',
            'subject' => 'בעיה בהטמעה',
            'message' => 'הווידג׳ט לא נטען כראוי בדפדפן שלי, נסיתי מספר דפדפנים.',
        ]);

        $response->assertRedirect(route('dashboard.support', ['site' => $site->id]));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'subject' => 'בעיה בהטמעה',
            'category' => 'technical',
            'priority' => 'normal',
            'status' => 'open',
        ]);
    }

    public function test_support_ticket_creation_sends_email_to_admin(): void
    {
        Mail::fake();

        [$user, $site] = $this->registerAndGetUser();

        $this->actingAs($user)->post('/dashboard/support/tickets', [
            'site_id' => $site->id,
            'category' => 'technical',
            'priority' => 'normal',
            'subject' => 'שאלה על הגדרות',
            'message' => 'איך משנים את מיקום הווידג׳ט בדפדפן ניידים? לא מצאתי הסבר.',
        ]);

        Mail::assertSent(SupportTicketCreated::class, function ($mail) {
            return $mail->hasTo(config('services.a11y_bridge.super_admin_email', 'office@brndini.co.il'));
        });
    }

    public function test_guest_cannot_create_support_ticket(): void
    {
        $response = $this->post('/dashboard/support/tickets', [
            'site_id' => 1,
            'category' => 'technical',
            'priority' => 'normal',
            'subject' => 'נסיון',
            'message' => 'ניסיון לפנות ללא התחברות.',
        ]);

        // The app redirects unauthenticated users to home (see Authenticate middleware)
        $response->assertRedirect(route('home'));
    }

    public function test_admin_can_respond_to_ticket_and_email_is_sent(): void
    {
        Mail::fake();

        [$user, $site] = $this->registerAndGetUser();

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'site_id' => $site->id,
            'reference_code' => 'SUP-00001',
            'subject' => 'בעיה בהתקנה',
            'category' => 'technical',
            'priority' => 'high',
            'status' => 'open',
            'message' => 'הקוד לא עובד.',
            'last_activity_at' => now(),
        ]);

        // Create a super admin user (email matches SUPER_ADMIN_EMAIL from config)
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => config('services.a11y_bridge.super_admin_email', 'office@brndini.co.il'),
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->actingAs($admin)->post('/dashboard/super-admin/tickets/' . $ticket->id, [
            'status' => 'resolved',
            'priority' => 'high',
            'admin_response' => 'בדקנו ומצאנו את הבעיה. נשלח לך פתרון מפורט.',
        ]);

        $response->assertRedirect(route('dashboard.super-admin'));

        Mail::assertSent(SupportTicketResponded::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_admin_response_without_text_does_not_send_email(): void
    {
        Mail::fake();

        [$user, $site] = $this->registerAndGetUser();

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'site_id' => $site->id,
            'reference_code' => 'SUP-00002',
            'subject' => 'בעיה',
            'category' => 'billing',
            'priority' => 'low',
            'status' => 'open',
            'message' => 'שאלה על חיוב.',
            'last_activity_at' => now(),
        ]);

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => config('services.a11y_bridge.super_admin_email', 'office@brndini.co.il'),
            'password' => bcrypt('admin123'),
        ]);

        $this->actingAs($admin)->post('/dashboard/super-admin/tickets/' . $ticket->id, [
            'status' => 'pending',
            'priority' => 'low',
            'admin_response' => '',
        ]);

        Mail::assertNotSent(SupportTicketResponded::class);
    }
}
