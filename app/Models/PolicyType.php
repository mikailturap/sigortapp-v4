<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PolicyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'is_active',
        'is_deletable'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deletable' => 'boolean',
        'sort_order' => 'integer'
    ];

    // İlişkiler
    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class, 'policy_type', 'name');
    }

    // Scope'lar
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessor'lar
    public function getCanDeleteAttribute(): bool
    {
        return $this->is_deletable && $this->policies()->count() === 0;
    }

    public function getCanDeactivateAttribute(): bool
    {
        return $this->policies()->count() > 0;
    }

    // Metodlar
    public function deactivate(): void
    {
        if ($this->can_deactivate) {
            $this->update(['is_active' => false]);
        }
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }
}
