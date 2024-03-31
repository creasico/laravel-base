<?php

namespace Creasi\Base\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return \view('creasi::pages.dashboard');
    }
}
