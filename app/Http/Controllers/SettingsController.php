<?php
/**
 * Created by Maksym Ignatchenko, Appus Studio LP on 26.11.2018
 *
 */

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserSettingsRequest;
use App\Location;
use App\Settings;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    /**
     * @var mixed
     */
    private $locationModel;

    /**
     * @var mixed
     */
    private $settingsModel;

    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
        $this->locationModel = app()[Location::class];
        $this->settingsModel = app()[Settings::class];
    }

    /**
     * @param UpdateUserSettingsRequest $request
     * @return Response
     */
    public function update(UpdateUserSettingsRequest $request): Response
    {
        $user = $request->user();
        if ($request->locations) {
            $locations = $this->locationModel->batchFirstOrCreate($request->locations);
            $user->locations()->sync($locations->pluck('id'));
        }
        $settings = $user->settings;
        if (!$settings) {
            $this->settingsModel->fill($request->all());
            $user->settings()->save($this->settingsModel);
        } else {
            $settings->update($request->all());
        }
        return ResponseBuilder::success();
    }
}