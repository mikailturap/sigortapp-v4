<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'customer_title', 'customer_identity_number', 'customer_phone', 'customer_birth_date', 'customer_address',
        'insured_name', 'insured_phone', 'policy_type', 'policy_number', 'policy_company',
        'plate_or_other', 'issue_date', 'start_date', 'end_date', 'document_info', 'status',
        'tarsim_business_number', 'tarsim_animal_number',
        // Gelir Yönetimi Alanları
        'policy_premium', 'commission_rate', 'commission_amount', 'net_revenue',
        'payment_status', 'payment_due_date', 'payment_date', 'payment_notes',
        'payment_method', 'invoice_number', 'tax_rate', 'tax_amount', 'total_amount',
        // Poliçe Maliyet Analizi
        'policy_cost', 'brokerage_fee', 'operational_cost', 'profit_margin',
        // Müşteri Cari Takibi
        'customer_balance', 'customer_payment_terms', 'customer_credit_limit',
        'last_payment_reference', 'last_payment_reminder'
    ];

    protected $casts = [
        'customer_birth_date' => 'date',
        'issue_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'notified_at' => 'datetime',
        'notification_dismissed_at' => 'datetime',
        'sms_reminder_sent_at' => 'datetime',
        'payment_due_date' => 'date',
        'payment_date' => 'date',
        'policy_premium' => 'float',
        'commission_rate' => 'float',
        'commission_amount' => 'float',
        'net_revenue' => 'float',
        'tax_rate' => 'float',
        'tax_amount' => 'float',
        'total_amount' => 'float',
        'policy_cost' => 'float',
        'brokerage_fee' => 'float',
        'operational_cost' => 'float',
        'profit_margin' => 'float',
        'customer_balance' => 'float',
        'customer_credit_limit' => 'integer',
        'last_payment_reminder' => 'datetime',
    ];

    // İlişkiler
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentSchedules(): HasMany
    {
        return $this->hasMany(PaymentSchedule::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PolicyFile::class);
    }

    // Gelir Yönetimi Metodları
    public function calculateCommission()
    {
        if ($this->policy_premium && $this->commission_rate) {
            $this->commission_amount = round((float) (($this->policy_premium * $this->commission_rate) / 100), 2);
            $this->save();
        }
        return $this->commission_amount;
    }

    public function calculateNetRevenue()
    {
        if ($this->policy_premium && $this->commission_amount) {
            $this->net_revenue = round((float) ($this->policy_premium - $this->commission_amount), 2);
            $this->save();
        }
        return $this->net_revenue;
    }

    public function calculateTax()
    {
        if ($this->net_revenue && $this->tax_rate) {
            $this->tax_amount = round((float) (($this->net_revenue * $this->tax_rate) / 100), 2);
            $this->save();
        }
        return $this->tax_amount;
    }

    public function calculateTotalAmount()
    {
        if ($this->net_revenue && $this->tax_amount) {
            $this->total_amount = round((float) ($this->net_revenue + $this->tax_amount), 2);
            $this->save();
        }
        return $this->total_amount;
    }

    public function isPaymentOverdue()
    {
        return $this->payment_due_date && $this->payment_due_date < now() && $this->payment_status !== 'ödendi';
    }

    public function getPaymentStatusColor()
    {
        return match($this->payment_status) {
            'ödendi' => 'success',
            'bekliyor' => 'warning',
            'gecikmiş' => 'danger',
            default => 'secondary'
        };
    }

    public function getPaymentStatusText()
    {
        return match($this->payment_status) {
            'ödendi' => 'Ödendi',
            'bekliyor' => 'Bekliyor',
            'gecikmiş' => 'Gecikmiş',
            default => 'Bilinmiyor'
        };
    }

    // Yeni Muhasebe ve Maliyet Analizi Metodları
    public function calculateProfitMargin()
    {
        if ($this->net_revenue && $this->policy_cost) {
            $this->profit_margin = $this->net_revenue - $this->policy_cost;
            $this->save();
        }
        return $this->profit_margin;
    }

    public function getProfitMarginPercentage()
    {
        if ($this->net_revenue && $this->profit_margin) {
            return round(($this->profit_margin / $this->net_revenue) * 100, 2);
        }
        return 0;
    }

    public function updateCustomerBalance()
    {
        if ($this->payment_status === 'ödendi') {
            $this->customer_balance = 0.0;
        } else {
            $this->customer_balance = round((float) ($this->total_amount ?? 0), 2);
        }
        $this->save();
        return $this->customer_balance;
    }

    public function getDaysOverdue()
    {
        if ($this->payment_due_date && $this->payment_status !== 'ödendi') {
            return now()->diffInDays($this->payment_due_date, false);
        }
        return 0;
    }

    public function getRiskLevel()
    {
        $daysOverdue = $this->getDaysOverdue();
        return match(true) {
            $daysOverdue <= 0 => 'düşük',
            $daysOverdue <= 30 => 'orta',
            $daysOverdue <= 90 => 'yüksek',
            default => 'kritik'
        };
    }

    // İlişkiler
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function costAnalysis()
    {
        return $this->hasMany(PolicyCostAnalysis::class);
    }

    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class, 'customer_identity_number', 'customer_identity_number');
    }

    // Scope'lar
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'ödendi');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'bekliyor');
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_due_date', '<', now())
                    ->where('payment_status', '!=', 'ödendi');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('payment_date', now()->month)
                    ->whereYear('payment_date', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('payment_date', now()->year);
    }

    public function scopeByRiskLevel($query, $riskLevel)
    {
        return $query->where('risk_level', $riskLevel);
    }

    public function scopeByCustomerBalance($query, $minBalance = 0)
    {
        return $query->where('customer_balance', '>=', $minBalance);
    }
}
