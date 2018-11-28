<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Controllers;

use App\Helpers\ApiCode;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Location;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function get(Request $request) : Response
    {
        $userModel = app()[User::class];
        $user = $userModel->where('deviceId', $request->header('deviceId'))
            ->with(['locations', 'settings'])
            ->first();
        return ResponseBuilder::success($user);
    }

    public function store(StoreUserRequest $request) : Response
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

        return ResponseBuilder::success();
    }

    public function update(UpdateUserRequest $request) : Response
    {
        $userModel = app()[User::class];
        $user = $userModel
            ->where('deviceId', $request->header('deviceId'))
            ->first();
        if (!$user) {
            return ResponseBuilder::error(ApiCode::DEVICE_NOT_FOUND);
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
        return ResponseBuilder::success();
    }
}
