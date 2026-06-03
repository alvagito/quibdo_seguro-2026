<?php

namespace App\Http\Controllers;

class PaginasController extends Controller
{
    public function inicio()
    {
        return view('inicio');
    }

    public function login()
    {
        return view('login');
    }
}
