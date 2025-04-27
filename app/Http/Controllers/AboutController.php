<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AboutController extends Controller
{
    public function index()
    {
        $title = 'About Us';
        return view("client.aboutPage.index", compact("title"));
    }

    // public function functionName(Request $request) {
    //     $users = User::query();

    //     $users = $this->filterIndex($request, $users);

    //     $users = $users->get();
    // }

    // private function filterIndex(Request $request, $users)
    // {
    //     if ($request->name) {
    //         $users = $users->where();
    //     }
    //     if ($request->name) {
    //         $users = $users->where();
    //     }
    //     if ($request->name) {
    //         $users = $users->where();
    //     }
    //     if ($request->name) {
    //         $users = $users->where();
    //     }

    //     return $users;
    // }
}
