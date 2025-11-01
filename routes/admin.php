<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminTourController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BlogCommentController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\PlaceImageController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyImageController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/* Invokable Controllers */
Route::get('/dashboard', AdminController::class)->name('dashboard');

/* Routes Resources */
Route::resources([
    'places' => PlaceController::class,
    'places-images' => PlaceImageController::class,
    'users' => UserController::class,
    'properties' => PropertyController::class,
    'properties-images' => PropertyImageController::class,
    'blogs' => BlogController::class,
    'blog-categories' => CategoryController::class,
    'blog-comments' => BlogCommentController::class,
    'roles' => RoleController::class,
    'permissions' => PermissionController::class,
]);

Route::resource('login', LoginController::class);

Route::group(['as' => 'requests.', 'prefix' => 'requests'], function () {
    Route::get('/', [RequestController::class, 'index'])->name('request');
    Route::get('/approve-host/{id}', [RequestController::class, 'approve'])->name('approve');
    Route::get('/aprrove/{id}', [RequestController::class, 'approve2'])->name('approve2');
    Route::get('/delete/{id}', [RequestController::class, 'delete'])->name('delete');
    Route::get('/aprroveTour/{id}', [RequestController::class, 'tourApprove'])->name('tourApprove');
    Route::get('/deleteTour/{id}', [RequestController::class, 'tourDelete'])->name('tourDelete');
    Route::get('/tour-details/{id}', [RequestController::class, 'tourDetails'])->name('tourDetails');
});

Route::group(['as' => 'tours.', 'prefix' => 'tours'], function () {
    Route::get('/', [AdminTourController::class, 'index'])->name('index');
    Route::get('/edit/{id}', [AdminTourController::class, 'editPage'])->name('editPage');
    Route::post('/editTour/{id}', [AdminTourController::class, 'edit'])->name('edit');
});

Route::group(['as' => 'bookings.', 'prefix' => 'bookings'], function () {
    Route::get('/', [AdminUserController::class, 'bookings'])->name('bookings');
});

Route::view('/notfound', 'client.errors.404')->name('notfound');

// Route::get('/notfound', function () {
//    return view('client.errors.404');
// });

// old routes

// Route::group(['as' => '', 'prefix' => ''], function () {
//     Route::get('/places', [AdminPlaceController::class, 'index'])->name('places.index');
//     Route::post('/places', [AdminPlaceController::class, 'store'])->name('places.store');
//     Route::get('/places/{place}/edit', [AdminPlaceController::class, 'edit'])->name('places.edit');
//     Route::delete('/places/{place}', [AdminPlaceController::class, 'destroy'])->name('places.destroy');
//     Route::put('/places/{place}', [AdminPlaceController::class, 'update'])->name('places.update');
// Route::get("/images", [AdminPlaceController::class, 'index'])->name('images.index');
// Route::post("/images", [AdminPlaceController::class, 'store'])->name('image.store');
// Route::delete('/images/{image}', [AdminPlaceController::class, 'destroy'])->name('images.destroy');
// });

// Route::group(['as' => 'users.'], function () {
//    Route::get('/users', [AdminUserController::class, 'index'])->name('index');
//    Route::get('/users/{id}', [AdminUserController::class, 'guideInfo'])->name('show');
//    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('edit');
//    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('update');
//    Route::delete('/users/{id}', [AdminUserController::class, 'block'])->name('destroy');
// });

//    Route::get('/', [AdminPropertyController::class, 'index'])->name('index');
//    Route::post("/store", [AdminPropertyController::class, "store"])->name('store');
//    Route::delete("/{id}", [AdminPropertyController::class, "destroy"])->name('destroy');
//    Route::get("/{id}/edit", [AdminPropertyController::class, 'edit'])->name('edit');
//    Route::put("/{id}", [AdminPropertyController::class, 'upload'])->name('upload');
// Route::get('/properties-images', [AdminPropertyController::class, 'index'])->name('index');
// Route::post("/properties-images", [AdminPropertyController::class, 'store'])->name("store");
// Route::delete("/destroy/{id}", [AdminPropertyController::class, 'destroy'])->name('destroy');
// Route::get('/', [AdminBlogController::class, 'index'])->name('index');
// Route::post("/", [AdminBlogController::class, 'store'])->name('store');
// Route::get("/{id}/edit", [AdminBlogController::class, 'edit'])->name('edit');
// Route::put("/{id}", [AdminBlogController::class, 'update'])->name('update');
// Route::delete("/{id}", [AdminBlogController::class, 'destroy'])->name('destroy');

// Route::group(['as' => 'places', 'prefix' => 'places'], function () {
//    Route::get('/', [AdminPlaceController::class, 'index'])->name('places.index');
//    Route::post('/', [AdminPlaceController::class, 'store'])->name('places.store');
//    Route::get('/{place}/edit', [AdminPlaceController::class, 'edit'])->name('places.edit');
//    Route::delete('/{place}', [AdminPlaceController::class, 'destroy'])->name('places.destroy');
//    Route::put('/{place}', [AdminPlaceController::class, 'update'])->name('places.update');
// });
// Route::group(['as' => 'blogs.', 'prefix' => 'blogs'], function () {
//    Route::group(['as' => 'comments.', 'prefix' => 'comments'], function () {
//        Route::get('/{id}', [AdminBlogController::class, 'comments'])->name('comments');
//        //    Route::group(['as' => 'blog-categories.', 'prefix' => 'category'], function () {
// //        Route::get('/', [AdminBlogController::class, 'index'])->name('index');
// //        Route::post('/', [AdminBlogController::class, 'store'])->name('store');
// //        Route::delete('/{id}', [AdminBlogController::class, 'destroy'])->name('destroy');
// //    });
//
//    });
// });
