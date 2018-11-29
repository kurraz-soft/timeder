<?php
/**
 * Created by PhpStorm.
 * User: Kurraz
 */

namespace App\Repositories;


use App\User;

class UserRepository
{
    public function getAll()
    {
        return User::all();
    }
}