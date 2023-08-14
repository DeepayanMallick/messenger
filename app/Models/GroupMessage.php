<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_name',
    ];

    public function groupConversations()
    {
        return $this->hasMany(GroupConversation::class, 'group_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'member_id');
    }
}
