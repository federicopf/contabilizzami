<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    /**
     * Mostra la vista index per il super admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Restituisce una vista chiamata "superadmin.index"
        return view('superadmin.index');
    }
}
