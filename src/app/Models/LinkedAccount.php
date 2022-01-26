<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkedAccount extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'provider_name',
        'provider_id',
        'token',
        'refresh_token',
        'expires_in',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
