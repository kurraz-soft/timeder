<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(UserRepository $repo)
    {
        return response()->json($repo->getAll());
    }
}
