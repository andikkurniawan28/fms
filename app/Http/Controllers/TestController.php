<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        User::insert([
            [
                'id' => 4,
                'name' => 'Master',
                'role_id' => 1,
                'username' => 'master',
                'password' => bcrypt('master'),
            ],
        ]);
    }
}
