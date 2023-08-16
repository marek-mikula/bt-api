<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        //
    }
}
