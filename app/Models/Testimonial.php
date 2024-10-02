<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    // Define the fillable fields
    protected $fillable = [
        'member_id',
        'statement',
        'url',
        'created_by',
        'updated_by'
    ];

    /**
     * Relationship between Testimonial and Member.
     * A testimonial belongs to a member.
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Relationship for the user who created the testimonial.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship for the user who updated the testimonial.
     */
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
