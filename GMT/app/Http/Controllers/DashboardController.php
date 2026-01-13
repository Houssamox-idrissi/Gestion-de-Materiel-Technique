<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    public function etudiantDashboard()
    {
        return view('dashboard.etudiant');
    }
}
