<?php

namespace App\Broadcasting;

use App\Models\User;

class MyPrivateChannel
{
    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user): array|bool
    {
        return true;
    }
}
