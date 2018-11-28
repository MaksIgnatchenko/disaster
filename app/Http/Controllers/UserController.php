<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Location;
use App\Settings;
use App\User;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user = app()[User::class];
        $user->fill($request->all());
        $user->save();

        $settings = app()[Settings::class];
        $settings->fill($request->all());
        $user->settings()->save($settings);

        $location = app()[Location::class];
        $locations = $location->insertOrCreate($request->locations);
        $user->locations()->sync($locations->pluck('id'));

        return response()->json($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $userModel = app()[User::class];
        $user = $userModel
            ->where('deviceId', $request->header('deviceId'))
            ->first();
        if (!$user) {
//            TODO
        }

        $user->update($request->all());

        if ($request->locations) {
            $location = app()[Location::class];
            $locations = $location->insertOrCreate($request->locations);
            $user->locations()->sync($locations->pluck('id'));
        }

        $settings = $user->settings;
        if (!$settings) {
            $settings = app()[Settings::class];
            $settings->fill($request->all());
            $user->settings()->save($settings);
        } else {
            $settings->update($request->all());
        }
        $user = \App\User::where('deviceId', $request->header('deviceId'))
            ->with(['locations', 'settings'])
            ->first();
        return response()->json($user);
    }
}
