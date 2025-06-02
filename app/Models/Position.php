<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <--- Import HasMany here

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_at',
        'soft_delete',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'soft_delete' => 'boolean',
    ];

    /**
     * Get the user who created the position.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the position.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the members that have this position.
     *
     * @return HasMany
     */
    public function members(): HasMany // <--- ADD THIS METHOD
    {
        // Assuming your 'members' table has a 'position_id' foreign key
        return $this->hasMany(Member::class, 'position_id')->where(['soft_delete'=>0]);
    }
}
