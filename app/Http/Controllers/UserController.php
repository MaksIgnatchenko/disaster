<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = app()[User::class];
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function get(Request $request): Response
    {
        return ResponseBuilder::success($request->user()->toArray());
    }

    /**
     * @param StoreUserRequest $request
     * @return Response
     */
    public function store(StoreUserRequest $request): Response
    {
        $user = $this->userModel->fill($request->all());
        $user->save();
        return ResponseBuilder::success();
    }

    /**
     * @param UpdateUserRequest $request
     * @return Response
     */
    public function update(UpdateUserRequest $request): Response
    {
        $request->user()->update($request->all());
        return ResponseBuilder::success();
    }
}
