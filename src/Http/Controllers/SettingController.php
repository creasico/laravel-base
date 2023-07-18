<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Requests\SettingRequest;
use Creasi\Base\Http\Resources\SettingResource;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * @return SettingResource
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return SettingResource::make($user);
    }

    /**
     * @return SettingResource
     */
    public function update(SettingRequest $request)
    {
        $user = $request->user();

        return SettingResource::make($user);
    }
}
