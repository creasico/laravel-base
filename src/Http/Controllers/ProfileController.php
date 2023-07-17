<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Controllers\Controller;
use Creasi\Base\Http\Requests\ProfileRequest;
use Creasi\Base\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return ProfileResource::make($user);
    }

    /**
     * @return \Inertia\Response
     */
    public function update(ProfileRequest $request)
    {
        $user = $request->user();

        return ProfileResource::make($user);
    }
}
