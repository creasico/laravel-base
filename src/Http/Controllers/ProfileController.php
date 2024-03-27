<?php

namespace Creasi\Base\Http\Controllers;

use Creasi\Base\Http\Requests\ProfileRequest;
use Creasi\Base\Http\Resources\ProfileResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @return ProfileResource
     */
    public function show(Request $request)
    {
        $user = $request->user();

        $user->load('identity');

        return ProfileResource::make($user);
    }

    /**
     * @return ProfileResource
     */
    public function update(ProfileRequest $request)
    {
        $user = $request->fulfill();

        return ProfileResource::make($user);
    }
}
