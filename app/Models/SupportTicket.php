<?php

namespace App\Models;

use App\Support\RuntimeStore;
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
        if (Schema::hasColumn('support_tickets', 'admin_response')) {
            return $this->admin_response;
        }

        $value = RuntimeStore::get('support-ticket-' . $this->id, 'admin_response');

        return is_string($value) && trim($value) !== '' ? $value : null;
    }
}
