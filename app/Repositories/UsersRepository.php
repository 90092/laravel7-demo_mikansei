<?php

namespace App\Repositories;

use App\User;

class UsersRepository
{
    public $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
}