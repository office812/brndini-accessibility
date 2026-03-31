<?php

namespace App\Models;

use App\Support\RuntimeStore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'site_id',
        'reference_code',
        'subject',
        'category',
        'priority',
        'status',
        'message',
        'admin_response',
        'assigned_user_id',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public static function tableAvailable(): bool
    {
        return Schema::hasTable('support_tickets');
    }

    public static function columnsAvailable(array $columns): bool
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn('support_tickets', $column)) {
                return false;
            }
        }

        return true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function runtimeAdminResponse(): ?string
    {
        if (static::columnsAvailable(['admin_response'])) {
            return $this->admin_response;
        }

        $value = RuntimeStore::get('support-ticket-' . $this->id, 'admin_response');

        return is_string($value) && trim($value) !== '' ? $value : null;
    }

    public static function runtimeScope(): string
    {
        return 'support-center';
    }

    public static function runtimeTickets(): Collection
    {
        $tickets = RuntimeStore::get(static::runtimeScope(), 'tickets', []);

        return collect(is_array($tickets) ? $tickets : []);
    }

    public static function storeRuntime(User $user, Site $site, array $validated): void
    {
        $scope = static::runtimeScope();
        $nextId = (int) RuntimeStore::get($scope, 'next_id', 1);
        $now = Carbon::now();

        $ticket = [
            'id' => $nextId,
            'key' => 'runtime-' . $nextId,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'site_id' => $site->id,
            'site_name' => $site->site_name,
            'reference_code' => 'SUP-R' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT),
            'subject' => trim((string) $validated['subject']),
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'message' => trim((string) $validated['message']),
            'admin_response' => null,
            'assigned_user_id' => null,
            'assigned_user_name' => null,
            'created_at' => $now->toIso8601String(),
            'last_activity_at' => $now->toIso8601String(),
        ];

        RuntimeStore::putMany($scope, [
            'next_id' => $nextId + 1,
            'tickets' => static::runtimeTickets()->push($ticket)->values()->all(),
        ]);
    }

    public static function updateRuntime(string $ticketKey, User $admin, array $validated): void
    {
        $scope = static::runtimeScope();
        $tickets = static::runtimeTickets()->map(function (array $ticket) use ($ticketKey, $admin, $validated) {
            if (($ticket['key'] ?? null) !== $ticketKey) {
                return $ticket;
            }

            $ticket['status'] = $validated['status'];
            $ticket['priority'] = $validated['priority'];
            $ticket['admin_response'] = trim((string) ($validated['admin_response'] ?? '')) ?: null;
            $ticket['assigned_user_id'] = $admin->id;
            $ticket['assigned_user_name'] = $admin->name;
            $ticket['last_activity_at'] = Carbon::now()->toIso8601String();

            return $ticket;
        })->values()->all();

        RuntimeStore::put($scope, 'tickets', $tickets);
    }

    public function present(): object
    {
        $lastActivity = $this->last_activity_at ?? $this->created_at;

        return (object) [
            'update_key' => (string) $this->id,
            'reference_code' => $this->reference_code,
            'subject' => $this->subject,
            'status' => $this->status,
            'priority' => $this->priority,
            'category' => $this->category,
            'message' => $this->message,
            'site_name' => $this->site?->site_name,
            'user_email' => $this->user?->email,
            'admin_response' => $this->runtimeAdminResponse(),
            'last_activity_label' => $lastActivity?->diffForHumans() ?? 'עודכן עכשיו',
            'sort_timestamp' => $lastActivity?->timestamp ?? 0,
        ];
    }

    public static function presentRuntime(array $ticket): object
    {
        $lastActivityAt = isset($ticket['last_activity_at']) ? Carbon::parse($ticket['last_activity_at']) : null;

        return (object) [
            'update_key' => (string) ($ticket['key'] ?? ('runtime-' . ($ticket['id'] ?? 'x'))),
            'reference_code' => $ticket['reference_code'] ?? 'SUP-RUNTIME',
            'subject' => $ticket['subject'] ?? 'פנייה ללא כותרת',
            'status' => $ticket['status'] ?? 'open',
            'priority' => $ticket['priority'] ?? 'normal',
            'category' => $ticket['category'] ?? 'general',
            'message' => $ticket['message'] ?? '',
            'site_name' => $ticket['site_name'] ?? null,
            'user_email' => $ticket['user_email'] ?? null,
            'admin_response' => $ticket['admin_response'] ?? null,
            'last_activity_label' => $lastActivityAt?->diffForHumans() ?? 'עודכן עכשיו',
            'sort_timestamp' => $lastActivityAt?->timestamp ?? 0,
        ];
    }
}
