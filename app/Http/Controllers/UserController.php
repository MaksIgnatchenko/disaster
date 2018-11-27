<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\User;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user = app()[User::class];
        $user->fill($request->all());
        $user->save();
    }

    public function update()
    {

    }
}
