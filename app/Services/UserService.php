<?php
/* 為了自幹註冊寫的，要刪掉嗎? */

namespace App\Services;

use App\Repositories\UsersRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function create($data)
    {
        $data['password'] = Hash::make($data['password']);

        $this->usersRepository->create($data);
    }
}