<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserOwnedObjectPolicy
{
    private const BYPASS_METHODS = [
        // Any user can create a new object
        'create',
    ];

    public function __call($name, $arguments)
    {
        if (in_array($name, self::BYPASS_METHODS, true)) {
            return true;
        }

        return $this->assert(...$arguments);
    }

    private function assert(User $user, Model $model): bool
    {
        if (isset($model->user_id)) {
            return $user->id === $model->user_id;
        }

        if ($model->isRelation('user')) {
            return $user->id === $model->user->id;
        }

        return false;
    }
}
