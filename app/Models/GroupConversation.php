<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'member_id',
        'message',
    ];

    public function GroupMessage()
    {
        return $this->belongsTo(GroupMessage::class, 'group_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
