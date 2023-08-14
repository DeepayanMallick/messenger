<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'member_id',
        'created_at',
        'updated_at',
    ];

    public function group()
    {
        return $this->belongsTo(GroupMessage::class, 'group_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
