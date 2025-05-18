<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostRequest;
use App\Models\Request;
use App\Models\Tour;
use App\Models\TourTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Request::all();
        $usersArr = Request::with('users')->get();
        $users = [];
        foreach ($usersArr as $item) {
            $user = $item->users;
            $users[] = $user;
        }
        $r_count = Request::all();
        $count = count($r_count);

        $tour = Tour::withTrashed()
            ->from('tours as tp')
            ->select('tp.id', 'u.name', 'tp.status')
            ->join('users as u', 'u.id', 'tp.host_id')
            ->orderBy('id')
            ->get();

        return view('admin.requests.index', compact(['users', 'requests', 'usersArr', 'tour', 'count']));
    }

    public function approve2($id)
    {
        $request = Request::where('id', $id)->first();
        $type = 'guide';
        $request->update([
            'type' => $type,
        ]);
        return back()->with("success", 'You give permission to become guide');
    }
    public function approve($id)
    {
        $user = User::where('id', $id)->with('requests')->first();
        $rId = $user->requests->id;
        $request = Request::findOrFail($rId);
        $user->update([
            'role' => "host"
        ]);
        $request->delete();
        if ($user && $request) {
            return back()->with('success', 'User role has uptaded to host');
        }
    }
    public function tourDetails($id)
    {
        $tour = Tour::withTrashed()
            ->with(['host', 'hotels.hotel', 'guides.guide', 'transaction'])
            ->where('id', $id)
            ->first();

        return view('admin.requests.details', compact(['tour']));
    }
    public function tourApprove($id)
    {
         $request = Tour::query()
            ->where('id', $id)
            ->with('host')
            ->first();

        if(!$request) {
            abort(404);
        }

        $request->update(['status' => 1]);

        $link = route('tourPlan.create', ['id' => $request->id, 'step' => 2]);
        $body = "Your tour has been approved successfully. Please click this link: <br> <a href=\"$link\">Click</a>";

        Mail::send('Mail.index', compact('body'), function ($mail) use ($request) {
            $mail->to($request->host->email)->subject("Tour Plan Approved");
        });


        return to_route('admin.requests.request')->with("success", 'You give permission to create tour');
    }

    public function delete($id)
    {
        $delete = Request::query()->findOrFail($id);
        $delete->delete();
        return back()->with('success', 'Request refused successfully');
    }
    public function tourDelete($id)
    {
        $tour_plan = Tour::query()->findOrFail($id);
        $tour_plan->delete();

        return back()->with('success', 'Tour deleted successfully!!');
    }

}
