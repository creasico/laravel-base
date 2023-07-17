<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Controllers\Controller;
use Creasi\Base\Http\Requests\SettingRequest;
use Creasi\Base\Http\Resources\SettingResource;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return SettingResource::make($user);
    }

    /**
     * @return \Inertia\Response
     */
    public function update(SettingRequest $request)
    {
        $user = $request->user();

        return SettingResource::make($user);
    }
}
