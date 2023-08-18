<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
