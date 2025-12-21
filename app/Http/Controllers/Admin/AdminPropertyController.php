<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Property\StoreRequest;
use App\Models\Property;
use App\Models\PropertyFile;
use App\Models\PropertySupply;
use App\Models\Supply;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;

class AdminPropertyController extends Controller
{
    use MediaTrait;

    public function index()
    {
        $place = Property::query()
            ->from('properties as p')
            ->select('p.*', 'pf.image as image')
            ->join('property_files as pf', 'pf.property_id', '=', 'p.id')
            ->where('pf.show_home', 1)
            ->paginate(6);

        $supplies = Supply::query()->get();

        return view('admin.property.index', compact(['place', 'supplies']));
    }

    //    public function store(StoreRequest $request)
    //    {
    //        $newFile = $this->uploadImage($request->file('image'), 'imgs');
    //
    //        $property = Property::create([
    //            'name' => $request->name,
    //            'description' => $request->description,
    //            'location' => $request->location,
    //            'stars' => $request->stars,
    //            'price' => $request->price,
    //            'bed_count' => $request->bed_count,
    //            'bath_count' => $request->bath_count,
    //            'sqft_count' => $request->sqft_count,
    //        ]);
    //
    //        PropertyFile::query()
    //            ->create([
    //                'image' => $newFile,
    //                'show_home' => 1,
    //                'property_id' => $property->id
    //            ]);
    //
    //        $supplArr = [];
    //        foreach ($request->supplies as $item)
    //        {
    //            $supplArr[] = [
    //                'property_id' => $property->id,
    //                'supply_id' => $item,
    //                'created_at' => now(),
    //                'updated_at' => now()
    //            ];
    //        }
    //
    //        PropertySupply::query()->insert($supplArr);
    //
    //        return back()->with('success', "Data Uploaded Successfully");
    //    }
    public function destroy($id)
    {
        $delete = Property::query()->findOrFail($id);

        if (! $delete) {
            return back()->with('error', 'Data Not Found');
        }

        $delete->delete();

        return back()->with('success', 'Deleted Successfully');
    }

    public function edit($id)
    {
        $item = Property::query()->with('supplies')
            ->from('properties as p')
            ->select('p.*', 'pf.image as image', 'pf.id as imageId')
            ->join('property_files as pf', 'pf.property_id', '=', 'p.id')
            ->where('pf.show_home', 1)
            ->where('p.id', $id)
            ->first();

        $supplies = Supply::query()->get();

        return view('admin.property.edit', compact('item', 'supplies'));
    }

    public function upload(UploadRequest $request, $id)
    {
        $item = Property::query()->findOrFail($id);

        $image = PropertyFile::query()
            ->where('property_id', $id)
            ->where('show_home', 1)
            ->first()->image;

        if ($request->hasFile('image')) {
            $image = $this->uploadImage($request->file('image'), 'imgs');
        }

        PropertyFile::query()
            ->where('property_id', $id)
            ->where('show_home', 1)
            ->update([
                'image' => $image,
            ]);

        PropertySupply::query()
            ->where('property_id', $id)
            ->delete();

        $item->update($request->validated());

        return back()->with('success', 'Updated successfully');
    }

    public function image(Request $request)
    {
        $id = $request->get('property_id');
        $files = PropertyFile::query()->where('property_id', $id)->get();
        $name = Property::query()->findOrFail($id)->name;

        return view('admin.property.image', compact(['files', 'id', 'name']));
    }

    public function store(Request $request, $id)
    {
        $imageArr = [];
        foreach ($request->file('file') as $file) {
            $newFile = $this->uploadImage($file, 'imgs');
            $imageArr[] = [
                'property_id' => $id,
                'image' => $newFile,
            ];
        }
        $create = PropertyFile::query()->insert($imageArr);
        if ($create) {
            return back()->with('success', 'Images added successsfully');
        }

        return back()->with('error', 'Please fill all inputs');
    }

    public function deleteImage($id)
    {
        $item = PropertyFile::findOrFail($id);
        $delete = $item->delete();
        if ($delete) {
            return back()->with('success', 'Image deleted successfully');
        }
    }
}

// if ($request->wifi) {
//    $wifi = 'true';
// } else {
//    $wifi = 'false';
// }
// if ($request->tv) {
//    $tv = 'true';
// } else {
//    $tv = 'false';
// }
// if ($request->free_parking) {
//    $free_parking = 'true';
// } else {
//    $free_parking = 'false';
// }
// if ($request->air_conditioner) {
//    $air_conditioner = 'true';
// } else {
//    $air_conditioner = 'false';
// }
// if ($request->pool) {
//    $pool = 'true';
// } else {
//    $pool = 'false';
// }
// if ($request->gym) {
//    $gym = 'true';
// } else {
//    $gym = 'false';
// }
// if ($request->kitchen) {
//    $kitchen = 'true';
// } else {
//    $kitchen = 'false';
// }
// if ($request->long_term) {
//    $long_term = 'true';
// } else {
//    $long_term = 'false';
// }
// if ($request->elevator) {
//    $elevator = 'true';
// } else {
//    $elevator = 'false';
// }
// if ($request->refrigerator) {
//    $refrigerator = 'true';
// } else {
//    $refrigerator = 'false';
// }
// if ($request->pet_allowed) {
//    $pet_allowed = 'true';
// } else {
//    $pet_allowed = 'false';
// }
// if ($request->washing_machine) {
//    $washing_machine = 'true';
// } else {
//    $washing_machine = 'false';
// }
// $extra = [
//    'wifi' => $wifi,
//    'tv' => $tv,
//    'free_parking' => $free_parking,
//    'air_conditioner' => $air_conditioner,
//    'pool' => $pool,
//    'gym' => $gym,
//    'kitchen' => $kitchen,
//    'long_term_stay' => $long_term,
//    'elevator' => $elevator,
//    'refrigerator' => $refrigerator,
//    'pet_allowed' => $pet_allowed,
//    'washing_machine' => $washing_machine
// ];
// $extraJson = json_encode($extra);
// if ($request->hasFile('image')) {
//    $file = $request->file('image');
//    $extension = $file->getClientOriginalExtension();
//    $newFile = time() . "." . $extension;
// }
// $insert = Property::create([
//    'name' => $request->name,
//    'description' => $request->description,
//    'location' => $request->location,
//    'stars' => $request->stars,
//    'price' => $request->price,
//    'bed_count' => $request->bed_count,
//    'bath_count' => $request->bath_count,
//    'sqft_count' => $request->sqft_count,
//    'image' => $newFile,
//    'extras' => $extraJson
// ]);
// $file->move(public_path('/images/imgs'), $newFile);
// if ($insert) {
//    return back()->with('success', "Data Uploaded Successfully");
// }
// }
