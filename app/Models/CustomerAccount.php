<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_identity_number', 'customer_title', 'current_balance', 'credit_limit',
        'payment_terms_days', 'payment_method_preference', 'risk_level', 'days_overdue',
        'last_payment_date', 'last_activity_date', 'phone', 'email', 'address',
        'accounting_code', 'tax_office', 'tax_number', 'notes', 'is_active'
    ];

    protected $casts = [
        'current_balance' => 'float',
        'credit_limit' => 'float',
        'payment_terms_days' => 'integer',
        'days_overdue' => 'integer',
        'last_payment_date' => 'datetime',
        'last_activity_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    // İlişkiler
    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class, 'customer_identity_number', 'customer_identity_number');
    }

    public function customer(): BelongsTo
    {
        // Tercihen customer_id üzerinden, geriye dönük uyum için kimlik no da tutuluyor
        return $this->belongsTo(Customer::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class, 'customer_identity_number', 'customer_identity_number');
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRiskLevel($query, $riskLevel)
    {
        return $query->where('risk_level', $riskLevel);
    }

    public function scopeOverdue($query)
    {
        return $query->where('days_overdue', '>', 0);
    }

    public function scopeByBalance($query, $minBalance = 0)
    {
        return $query->where('current_balance', '>=', $minBalance);
    }

    public function scopeByCreditLimit($query, $minLimit = 0)
    {
        return $query->where('credit_limit', '>=', $minLimit);
    }

    // Accessor'lar
    public function getFormattedBalanceAttribute()
    {
        return number_format((float) $this->current_balance, 2) . ' ₺';
    }

    public function getFormattedCreditLimitAttribute()
    {
        return number_format((float) $this->credit_limit, 2) . ' ₺';
    }

    public function getRiskColorAttribute()
    {
        return match($this->risk_level) {
            'düşük' => 'success',
            'orta' => 'warning',
            'yüksek' => 'danger',
            'kritik' => 'dark',
            default => 'secondary'
        };
    }

    public function getRiskTextAttribute()
    {
        return match($this->risk_level) {
            'düşük' => 'Düşük Risk',
            'orta' => 'Orta Risk',
            'yüksek' => 'Yüksek Risk',
            'kritik' => 'Kritik Risk',
            default => 'Bilinmiyor'
        };
    }

    public function getAvailableCreditAttribute()
    {
        return max(0, $this->credit_limit - $this->current_balance);
    }

    public function getCreditUtilizationAttribute()
    {
        if ($this->credit_limit > 0) {
            return round(($this->current_balance / $this->credit_limit) * 100, 2);
        }
        return 0;
    }

    // Metodlar
    public function updateBalance($amount, $type = 'add')
    {
        if ($type === 'add') {
            $this->current_balance = (string) number_format(((float) $this->current_balance + (float) $amount), 2, '.', '');
        } else {
            $this->current_balance = (string) number_format(((float) $this->current_balance - (float) $amount), 2, '.', '');
        }
        
        $this->last_activity_date = now();
        $this->save();
        
        return $this->current_balance;
    }

    public function updateRiskLevel()
    {
        $this->risk_level = $this->calculateRiskLevel();
        $this->save();
        
        return $this->risk_level;
    }

    public function calculateRiskLevel()
    {
        if ($this->days_overdue > 90) return 'kritik';
        if ($this->days_overdue > 60) return 'yüksek';
        if ($this->days_overdue > 30) return 'orta';
        if ($this->days_overdue > 0) return 'düşük';
        
        $utilization = $this->credit_utilization;
        if ($utilization > 80) return 'orta';
        if ($utilization > 60) return 'düşük';
        
        return 'düşük';
    }

    public function isOverdue()
    {
        return $this->days_overdue > 0;
    }

    public function canExtendCredit($amount)
    {
        return $this->available_credit >= $amount;
    }
}
