<?php

namespace App\Http\Controllers;

use App\Traits\SecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    use SecurityHeaders;
    public function index(): View
    {
        $response = response(view('home'));
        return $this->addSecurityHeaders($response)->original;
    }
}
