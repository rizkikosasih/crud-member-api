<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'phone', 'email', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class, 'member_hobby');
    }
}
