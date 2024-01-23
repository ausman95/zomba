<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        activity('Settings')
            ->log("Accessed the settings page")->causer(request()->user());
        return view('settings')->with([
            'cpage' => "settings",
        ]);
    }
}
