<?php

namespace App\Http\Controllers;

use App\Traits\SecurityHeaders;

class ExploreController
{
    use SecurityHeaders;
    public function index()
    {
        return view('explore');
    }
}
