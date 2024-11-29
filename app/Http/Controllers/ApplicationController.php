<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    public function show_application_page(Request $request)
    {

        return Inertia::render('Application/Application');
    }
}
