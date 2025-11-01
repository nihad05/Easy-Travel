<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    public function __invoke(): Factory|View|Application
    {
        $users_count = User::query()->count();
        $requests_count = Request::query()->count();
        $tours_count = Tour::query()->count();

        return view('admin.dashboard.index', compact(['users_count', 'requests_count', 'tours_count']));
    }
}
