<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'policy_id',
        'description',
        'amount',
        'due_date',
        'status',
        'payment_type',
        'installment_number',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    // İlişkiler
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Accessor'lar
    public function getIsOverdueAttribute()
    {
        return $this->status === 'bekliyor' && $this->due_date < now();
    }

    public function getDaysUntilDueAttribute()
    {
        if ($this->status !== 'bekliyor') {
            return 0;
        }
        
        return now()->diffInDays($this->due_date, false);
    }

    public function getFormattedAmountAttribute()
    {
        return '₺' . number_format($this->amount, 2, ',', '.');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'bekliyor' => 'warning',
            'ödendi' => 'success',
            'gecikti' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return ucfirst($this->status);
    }

    // Scope'lar
    public function scopePending($query)
    {
        return $query->where('status', 'bekliyor');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'bekliyor')
            ->where('due_date', '<', now());
    }

    public function scopeDueSoon($query, $days = 7)
    {
        return $query->where('status', 'bekliyor')
            ->where('due_date', '<=', now()->addDays($days))
            ->where('due_date', '>=', now());
    }
}
