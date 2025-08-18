<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'original_name',
        'path',
        'disk',
        'mime_type',
        'size',
    ];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}


