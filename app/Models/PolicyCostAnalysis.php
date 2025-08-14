<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyCostAnalysis extends Model
{
    use HasFactory;

    protected $table = 'policy_cost_analysis';

    protected $fillable = [
        'policy_id', 'insurance_company_cost', 'brokerage_cost', 'operational_cost',
        'marketing_cost', 'administrative_cost', 'gross_revenue', 'net_revenue',
        'profit_margin', 'profit_margin_percentage', 'risk_reserve', 'commission_reserve',
        'tax_reserve', 'analysis_date', 'analysis_period', 'notes'
    ];

    protected $casts = [
        'insurance_company_cost' => 'decimal:2',
        'brokerage_cost' => 'decimal:2',
        'operational_cost' => 'decimal:2',
        'marketing_cost' => 'decimal:2',
        'administrative_cost' => 'decimal:2',
        'gross_revenue' => 'decimal:2',
        'net_revenue' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'profit_margin_percentage' => 'decimal:2',
        'risk_reserve' => 'decimal:2',
        'commission_reserve' => 'decimal:2',
        'tax_reserve' => 'decimal:2',
        'analysis_date' => 'datetime'
    ];

    // İlişkiler
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    // Scope'lar
    public function scopeByPeriod($query, $period)
    {
        return $query->where('analysis_period', $period);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('analysis_date', [$startDate, $endDate]);
    }

    public function scopeProfitable($query)
    {
        return $query->where('profit_margin', '>', 0);
    }

    public function scopeByProfitMargin($query, $minMargin)
    {
        return $query->where('profit_margin_percentage', '>=', $minMargin);
    }

    // Accessor'lar
    public function getTotalCostAttribute()
    {
        return ($this->insurance_company_cost ?? 0) +
               ($this->brokerage_cost ?? 0) +
               ($this->operational_cost ?? 0) +
               ($this->marketing_cost ?? 0) +
               ($this->administrative_cost ?? 0);
    }

    public function getTotalReserveAttribute()
    {
        return ($this->risk_reserve ?? 0) +
               ($this->commission_reserve ?? 0) +
               ($this->tax_reserve ?? 0);
    }

    public function getNetProfitAttribute()
    {
        return ($this->net_revenue ?? 0) - $this->total_cost;
    }

    public function getProfitabilityRatioAttribute()
    {
        if ($this->gross_revenue && $this->gross_revenue > 0) {
            return round(($this->net_revenue / $this->gross_revenue) * 100, 2);
        }
        return 0;
    }
}
