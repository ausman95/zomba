<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRevaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'asset_id',
        'amount',
        'created_by',
        'updated_by',
        // Any other fields that should be mass assignable
    ];
    public function asset()
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
