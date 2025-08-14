<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_title',
        'customer_identity_number',
        'phone',
        'email',
        'address',
        'credit_limit',
        'risk_level',
        'customer_type',

        'notes'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    // İlişkiler
    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function paymentSchedules(): HasMany
    {
        return $this->hasMany(PaymentSchedule::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Accessor'lar
    public function getCurrentBalanceAttribute()
    {
        $totalScheduled = $this->paymentSchedules()
            ->where('status', 'bekliyor')
            ->sum('amount');
        
        $totalPaid = $this->payments()
            ->where('payment_status', 'tamamlandı')
            ->sum('amount');
        
        return $totalScheduled - $totalPaid;
    }

    public function getOverdueAmountAttribute()
    {
        return $this->paymentSchedules()
            ->where('status', 'gecikti')
            ->sum('amount');
    }

    public function getDaysOverdueAttribute()
    {
        $overdueSchedule = $this->paymentSchedules()
            ->where('status', 'gecikti')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->first();
        
        if (!$overdueSchedule) {
            return 0;
        }
        
        return now()->diffInDays($overdueSchedule->due_date);
    }

    public function getFormattedBalanceAttribute()
    {
        return '₺' . number_format($this->current_balance, 2, ',', '.');
    }





    public function getCreditUtilizationAttribute()
    {
        if ($this->credit_limit <= 0) {
            return 0;
        }
        
        return min(100, ($this->current_balance / $this->credit_limit) * 100);
    }





    public function scopeOverdue($query)
    {
        return $query->whereHas('paymentSchedules', function($q) {
            $q->where('status', 'gecikti');
        });
    }
}
