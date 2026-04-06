<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);
        $email = strtolower(trim($request->email));

        try {
            DB::table('newsletter_subscribers')->updateOrInsert(
                ['email' => $email],
                ['email' => $email, 'subscribed_at' => now(), 'source' => 'homepage']
            );
        } catch (\Throwable $e) {
            // Table might not exist yet — log silently
            Log::info('Newsletter subscribe (no table): ' . $email);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('newsletter_success', 'נרשמת בהצלחה! נשלח לך עדכון כשמוצרים חדשים יוצאים.');
    }
}
