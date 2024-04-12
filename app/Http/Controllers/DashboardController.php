<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'total_projects' => Project::all()->count(),
            'total_users' => User::all()->count(),
        ]);
    }
}