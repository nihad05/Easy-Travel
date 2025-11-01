<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Traits\Messages;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    use Messages;

    public function index(): Application|Factory|View
    {
        $category = BlogCategory::all();

        return view('admin.blogs.category.index', compact(['category']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $category = BlogCategory::query()
            ->where(DB::raw('LOWER(name)'), strtolower($name))
            ->exists();
        if ($category) {
            return back()->with('error', 'Category already exists');
        }

        $insert = BlogCategory::query()->create([
            'name' => $name,
        ]);
        if ($insert) {
            return back()->with('success', 'Category added Blogs successfully');
        }

        return back()->with('error', $this::$WRONG);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id): RedirectResponse
    {
        $category = BlogCategory::query()->findOrFail($id);

        if (! $category) {
            return back()->with('error', 'Category not found');
        }

        $delete = $category->delete();

        if ($delete) {
            return back()->with('success', 'Category deleted successfully');
        }

        return back()->with('error', $this::$WRONG);
    }
}
