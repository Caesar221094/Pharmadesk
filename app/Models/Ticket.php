<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'pharmacy_id',
        'customer_id',
        'expert_id',
        'tech_id',
        'module_id',
        'category_id',
        'title',
        'description',
        'app_version',
        'priority',
        'status',
        'source',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function getStatusLabelAttribute(): string
    {
        $map = [
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'for_review' => 'Pending Review',
            'closed' => 'Closed',
        ];

        return $map[$this->status] ?? ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(TicketModule::class, 'module_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function tech(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(TicketActivity::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class);
    }
}
