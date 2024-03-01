<?php

namespace App\Http\Controllers;


use Illuminate\View\View;

class MainPageController extends Controller
{
    /**
     * Index page of the site.
     */
    public function index(): View
    {
        return view('main-page');
    }
}
