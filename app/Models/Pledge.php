<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pledge extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id', 'member_id','created_by','updated_by','amount','date','type'
    ];
    public function account(): BelongsTo
    {
        return $this->belongsTo(Accounts::class,'account_id');
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class,'member_id');
    }
}
