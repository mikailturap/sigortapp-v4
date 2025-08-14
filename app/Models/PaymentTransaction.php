<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id', 'user_id', 'transaction_type', 'amount', 'currency',
        'payment_method', 'payment_status', 'reference_number', 'invoice_number',
        'invoice_date', 'accounting_code', 'transaction_date', 'processed_at',
        'notes', 'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'processed_at' => 'datetime',
        'invoice_date' => 'date',
        'metadata' => 'array'
    ];

    // İlişkiler
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope'lar
    public function scopeSuccessful($query)
    {
        return $query->where('payment_status', 'başarılı');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'bekliyor');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    // Accessor'lar
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getStatusColorAttribute()
    {
        return match($this->payment_status) {
            'başarılı' => 'success',
            'bekliyor' => 'warning',
            'başarısız' => 'danger',
            'iptal' => 'secondary',
            default => 'info'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->payment_status) {
            'başarılı' => 'Başarılı',
            'bekliyor' => 'Bekliyor',
            'başarısız' => 'Başarısız',
            'iptal' => 'İptal',
            default => 'Bilinmiyor'
        };
    }
}
