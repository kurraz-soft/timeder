<?php
/**
 * Created by PhpStorm.
 * User: Kurraz
 */

namespace App\Http\Controllers;


use App\Repositories\UserRepository;

class DashboardController extends Controller
{
    public function index(UserRepository $user_repo)
    {
        $users = $user_repo->getAll();

        return view('dashboard', [
            'users' => $users,
        ]);
    }
}