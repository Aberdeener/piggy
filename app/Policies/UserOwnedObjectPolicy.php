<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserOwnedObjectPolicy
{
    public function __call($name, $arguments)
    {
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
