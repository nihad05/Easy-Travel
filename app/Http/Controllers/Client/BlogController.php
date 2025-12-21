<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index()
    {
        $title = 'Blogs';
        $blogs = Blog::query()
            ->with('comments')
            ->paginate(5);

        $category = BlogCategory::all();

        $recoBlogs = Blog::query()
            ->orderBy('id')
            ->take(5)
            ->get();

        return view('client.blogs.index', compact(['blogs', 'recoBlogs', 'title', 'category']));
    }

    public function details($id): View
    {
        $blog = Blog::with('author')->where('id', $id)->first();

        if (auth()->user() && auth()->user() != null) {
            $image = User::query()->findOrFail(auth()->id())->image;
        }

        $title = $blog->name;
        $comments = Comment::query()
            ->with('users')
            ->where('entity_type', 'blog')
            ->where('entity_id', $id)
            ->get();

        if (auth()->user() && auth()->user() != null) {
            return view('client.blogs.details', compact(['image', 'blog', 'comments', 'title']));
        } else {
            return view('client.blogs.details', compact(['blog', 'comments', 'title']));
        }
    }

    public function create(Request $request)
    {
        $user = User::findOrFail(auth()->id());
        if ($user->role == 'guide') {
            $c_id = '3';
        } elseif ($user->role == 'host') {
            $c_id = '4';
        }
        $request->validate([
            'blogName' => ['required'],
            'shortDescription' => ['required'],
            'blogDescription' => ['required'],
            'blogImage' => ['required'],
        ]);
        if ($request->hasFile('blogImage')) {
            $file = $request->file('blogImage');
            $extension = $file->getClientOriginalExtension();
            $newFile = time().'.'.$extension;
            $file->move(public_path('/images/blogImgs'), $newFile);
        }
        $insert = Blog::create([
            'name' => $request->blogName,
            'short_description' => $request->shortDescription,
            'description' => $request->blogDescription,
            'category_id' => $c_id,
            'user_id' => auth()->id(),
            'image' => $newFile,
        ]);
        if ($insert) {
            return back()->with('success', 'Your blog has added successfully');
        }
    }

    public function search()
    {
        if (request()->has('category') && request()->get('category') != null) {
            $params = request()->get('category');
            $blog = BlogCategory::where('name', $params)->with('blogs')->get();
            $blogs = null;
            foreach ($blog as $item) {
                $blogs = $item['blogs'];
            }

            return response()->json([
                'data' => $blogs,
                'status' => 200,
            ], 200);
        }
    }
}
