<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'policy_id',
        'payment_schedule_id',
        'payment_number',
        'amount',
        'payment_method',
        'payment_status',
        'payment_date',
        'reference_number',
        'notes',
        'transaction_type',
        'transaction_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'transaction_date' => 'datetime',
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

    public function paymentSchedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class);
    }

    // Accessor'lar
    public function getFormattedAmountAttribute()
    {
        return '₺' . number_format($this->amount, 2, ',', '.');
    }

    public function getStatusColorAttribute()
    {
        return match($this->payment_status) {
            'tamamlandı' => 'success',
            'iptal' => 'danger',
            'iade' => 'warning',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return ucfirst($this->payment_status);
    }
    
    public function getTransactionTypeAttribute()
    {
        return $this->transaction_type ?? 'ödeme';
    }
    
    public function getTransactionDateAttribute()
    {
        return $this->transaction_date ?? $this->created_at;
    }

    public function getMethodIconAttribute()
    {
        return match($this->payment_method) {
            'nakit' => 'fas fa-money-bill-wave',
            'kredi kartı' => 'fas fa-credit-card',
            'banka havalesi' => 'fas fa-university',
            'çek' => 'fas fa-money-check',
            default => 'fas fa-money-bill'
        };
    }

    // Scope'lar
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'tamamlandı');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = 'PAY-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
