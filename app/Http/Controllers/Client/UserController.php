<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BookProperty;
use App\Models\GuideBook;
use App\Models\GuideInfo;
use App\Models\Request;
use App\Models\Tour;
use App\Models\TourUser;
use App\Models\User;

class UserController extends Controller
{
    public function index($id)
    {

        $activeHotelsBookings = $this->hotelBookings(0);
        $pastHotelsBookings = $this->hotelBookings(1);

        $activeGuideBookings = $this->guideBookings(0);
        $pastGuideBookings = $this->guideBookings(1);

        $activeTour = $this->tours(1);
        $pastTour = $this->tours(0);

        $user = User::query()->findOrFail($id);

        $requests = User::with('requests')->where('id', $id)->first();

        $request = $requests->requests;

        return view('client.userPage.index', compact([
            'user',
            'request',
            'activeGuideBookings',
            'pastGuideBookings',
            'activeHotelsBookings',
            'pastHotelsBookings',
            'activeTour',
            'pastTour',
        ]));
    }

    private function hotelBookings($status)
    {
        return BookProperty::query()
            ->from('book_properties as bp')
            ->select(
                'p.name',
                'p.price',
                'p.location',
                'pf.image',
                'p.id as p_id'
            )
            ->join('properties as p', 'p.id', 'bp.hotel_id')
            ->join('property_files as pf', function ($propertyFile) {
                $propertyFile->on('pf.property_id', 'p.id')->where('pf.show_home', 1);
            })
            ->where(['bp.user_id' => auth()->id(), 'bp.is_active' => $status])
            ->get();
    }

    private function guideBookings($status)
    {
        return GuideBook::query()
            ->from('guide_books as gb')
            ->select(
                'gb.id as guide_order_id',
                'u.id as user_id',
                'gb.start_date',
                'gb.end_date',
                'u2.name as guide_name',
                'u2.id as guide_id',
                'gi.price',
                'u2.image',
                'u2.location',
            )
            ->join('users as u', 'u.id', 'gb.user_id')
            ->join('users as u2', function ($guides) {
                $guides->on('u2.id', 'gb.guide_id')->where('u2.role', 'guide');
            })
            ->join('guide_infos as gi', 'gi.user_id', 'guide_id')
            ->where(['gb.user_id' => auth()->id(), 'gb.is_active' => $status])
            ->get();
    }

    private function tours($status)
    {
        return TourUser::query()
            ->from('tour_users as tu')
            ->select('t.name', 't.price', 't.start_time', 't.id as tour_id', 't.start_location', 't.image')
            ->join('tours as t', 't.id', 'tu.tour_id')
            ->where(['tu.user_id' => auth()->id(), 't.status' => $status])
            ->get();
    }

    public function request($id)
    {
        $insert = Request::create([
            'user_id' => auth()->user()->id,
        ]);
        if ($insert) {
            return back()->with('success', 'Your request has accepted successfully!');
        }
    }

    public function editProfile(\Illuminate\Http\Request $request, $id)
    {
        $user = User::findOrFail($id);
        $password = $user->password;
        $request->validate([
            'name' => ['required'],
            'location' => ['required'],
            'email' => ['required'],
        ]);
        if ($request->hasFile('image')) {
            $file = request()->file('image');
            $extension = $file->getClientOriginalExtension();
            $newFile = time().'.'.$extension;
            $file->move(public_path('/images/userImgs'), $newFile);
        } else {
            $newFile = $user->image;
        }

        $user->update([
            'name' => $request->name,
            'location' => $request->location,
            'email' => $request->email,
            'image' => $newFile,
            'password' => $password,
        ]);

        return back()->with('success', 'User informations changed successfully!');
    }

    public function guide(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'price' => ['required', 'min:0', 'integer'],
            'language' => ['required'],
            'aviable' => ['required'],
            'about' => ['required'],
        ]);
        $price = $request->price;
        $language = json_encode($request->language);
        $aviable = json_encode($request->aviable);
        $about = $request->about;
        $insert = GuideInfo::create([
            'price' => $price,
            'languages' => $language,
            'aviable_for' => $aviable,
            'about' => $about,
            'user_id' => auth()->id(),
        ]);
        $user = User::findOrFail(auth()->id());
        $guide = 'guide';
        $user->update([
            'role' => $guide,
        ]);
        $request = Request::where('user_id', auth()->id())->first()->delete();
        if ($insert && $request) {
            return back()->with('success', 'You have been guide!');
        }
    }

    public function editGuide(\Illuminate\Http\Request $request, $id)
    {
        $guide = User::findOrFail($id);
        $guideInfo = GuideInfo::where('user_id', $id)->first();
        $request->validate([
            'name' => ['required'],
            'location' => ['required'],
            'email' => ['required'],
            'about' => ['required'],
            'aviable' => ['required'],
            'language' => ['required'],
            'price' => ['required'],
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $newFile = time().'.'.$extension;
            $file->move(public_path('/images/userImgs'), $newFile);
        } else {
            $newFile = $guide->image;
        }
        $user = [
            'name' => $request->name,
            'location' => $request->location,
            'image' => $newFile,
            'email' => $request->email,
        ];
        $language = json_encode($request->language);
        $aviable = json_encode($request->aviable);
        $guideArr = [
            'about' => $request->about,
            'aviable_for' => $aviable,
            'languages' => $language,
            'price' => $request->price,
            'user_id' => $id,
        ];
        if ($user && $guideArr) {
            $guide->update($user);
            $guideInfo->update($guideArr);

            return back()->with('success', 'Your informations changed successfully');
        }

    }

    public function host($id)
    {
        $request = Request::create([
            'type' => 'host',
            'user_id' => $id,
        ]);
        if ($request) {
            return back()->with('success', 'Your request to be host has accepted !');
        }
    }

    public function userBlogEdit($id)
    {
        $blogs = Blog::with('author')->where('user_id', $id)->get();
        $categories = BlogCategory::all();
        $title = 'Edit Blog';

        return view('client.userPage.editBlog', compact(['blogs', 'title', 'categories']));
    }

    public function editedBlog(\Illuminate\Http\Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $newFile = time().'.'.$extension;
            $file->move(public_path('/images/blogImgs'), $newFile);
        } else {
            $newFile = $blog->image;
        }
        $editedArr = [
            'name' => $request->blogName,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $newFile,
            'category_id' => $blog->category_id,
        ];
        $blog->update($editedArr);

        return back()->with('success', 'Your blog updated successfully!');
    }

    public function editPage($id)
    {
        $blog = Blog::findOrFail($id);
        $title = 'Edit'.$blog->name;

        return view('client.userPage.editPage', compact(['title', 'blog']));
    }

    public function deleteBlog($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog != null) {
            $blog->delete();

            return back()->with('success', 'Blog deleted successfully');
        }
    }

    public function tourEdit($id)
    {
        $blog = Tour::withTrashed()->findOrFail($id);
        $title = 'Edit Tour';

        return view('client.userPage.editTour', compact('blog', 'title'));
    }

    public function tourPage($id) {}

    public function tour_edit(\Illuminate\Http\Request $request, $id)
    {
        $tour = Tour::findOrFail($id);
        $request->validate([
            'tourName' => ['string', 'required'],
            'tourPrice' => ['integer', 'required'],
            'people' => ['integer', 'required'],
            'startTime' => ['required'],
            'endTime' => ['required'],
            'about' => ['required'],
            'location' => ['required'],
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $newFile = time().'.'.$extension;
            $file->move(public_path('/images/tourImgs/'), $newFile);
        } else {
            $newFile = $tour->image;
        }
        $editedArr = [
            'name' => $request->tourName,
            'about' => $request->about,
            'price' => $request->tourPrice,
            'start_location' => $request->location,
            'start_time' => $request->startTime,
            'end_time' => $request->endTime,
            'people' => $request->people,
            'image' => $newFile,
        ];
        $tour->update($editedArr);

        return back()->with('success', 'Tour Updated Successful!');
    }

    public function deleteTour($id)
    {
        $tour = Tour::findOrFail($id)->delete();

        return back()->with('success', 'Tour deleted successfully');
    }
}
