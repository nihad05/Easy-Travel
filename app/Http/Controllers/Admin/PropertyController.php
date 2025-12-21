<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Property\StoreRequest;
use App\Http\Requests\Admin\Property\UpdateRequest;
use App\Models\Property;
use App\Models\PropertyFile;
use App\Models\PropertySupply;
use App\Models\Supply;
use App\Traits\MediaTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PropertyController extends Controller
{
    use MediaTrait;

    public function index(): Application|Factory|View
    {
        $place = Property::query()->with('homeImage')->fastPaginate(6);
        $supplies = Supply::query()->get();

        return view('admin.property.index', compact(['place', 'supplies']));
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

    public function store(StoreRequest $request): RedirectResponse
    {
        $newFile = $this->uploadImage($request->file('image'), 'imgs');
        $property = Property::query()->create($request->validated());

        PropertyFile::query()->create([
            'image' => $newFile,
            'show_home' => 1,
            'property_id' => $property->id,
        ]);

        $supplArr = [];
        foreach ($request->get('supplies') ?? [] as $item) {
            $supplArr[] = [
                'property_id' => $property->id,
                'supply_id' => $item,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        PropertySupply::query()->insert($supplArr);

        return back()->with('success', 'Data Uploaded Successfully');
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
     * @return Application|Factory|View
     */
    public function edit($id)// : Application|Factory|View
    {
        /**
         * with :
         *
         * select *
         * from properties as p
         * left join property_files as pf on pf.property_id = p.id
         * where p.id = 19
         * has :
         *
         * select p.*
         * from properties as p
         * inner join property_files as pf on pf.property_id = p.id
         * where p.id = 19
         *
         * whereHas :
         *
         * select p.*
         * from properties as p
         * inner join (
         * select p.*, pf1.property_id
         * from properties as p
         * inner join property_files as pf1 on pf1.property_id = p.id
         * where pf1.created_at = '2023-12-22T15:23:36.000000Z'
         * ) as pf on pf.property_id = p.id
         * where p.id = 19
         *
         *
         *
         *
         *
         *
         * --  comments  --- guide
         * --
         * ---
         * --- guide
         * ----
         *
         * SELECT *
         * FROM users as u
         * left join comments as c on c.user_id = u.id
         * where u.email = 'nihad@gmail.com' and c.entity_type = 'guide'
         *
         *
         * -- comments
         * --- guide
         *
         *
         *
         * SELECT *
         * FROM users as u
         * left join (
         * SELECT c.user_id as nihad, c.entity_type
         * FROM users as u
         * left join comments as c on c.user_id = u.id and c.entity_type ='guide'
         * ) as c on c.nihad = u.id
         * where u.email = 'nihad@gmail.com'
         */
        $item = Property::query()
            ->with('homeImage') // ile
//            ->with(['supplies:property_id,supply_id', 'image']) //left join
// //            ->with('supplies', function ($query) {
// //                $query->select('property_id', 'supply_id');
// //            })
//                ->has('supplies') //inner join - Property-ni getir hardaki supplies-i var(bos deyil)
//            ->whereHas('image', function($image) {//inner join - Property-ni getir hardaki supplies-i var(bos deyil) ve bu sertnen
//                    $image->where('created_at', '2023-12-22T15:23:36.000000Z');
//            })
//            ->whereHas()
            ->find($id);

        $supplies = Supply::query()->get();

        return view('admin.property.edit', compact('item', 'supplies'));
    }

    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $property = Property::query()->findOrFail($id);

        $image = PropertyFile::query()
            ->where('property_id', $id)
            ->where('show_home', 1)
            ->first()->image;

        if ($request->hasFile('image')) {
            $image = $this->uploadImage($request->file('image'), 'imgs');
        }

        $property->update($request->validated());

        PropertyFile::query()
            ->where('property_id', $id)
            ->where('show_home', 1)
            ->update([
                'image' => $image,
            ]);

        PropertySupply::query()
            ->where('property_id', $id)
            ->delete();

        $supplyArr = [];
        foreach ($request->supplies as $item) {
            $supplyArr[] = [
                'supply_id' => $item,
                'property_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        PropertySupply::query()->insert($supplyArr);

        return back()->with('success', 'Updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        $delete = Property::query()->findOrFail($id);

        if (! $delete) {
            return back()->with('error', 'Data Not Found');
        }

        $delete->delete();

        return back()->with('success', 'Deleted Successfully');
    }
}
