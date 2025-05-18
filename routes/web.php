<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Client\{
    BlogController,
    CommentsController,
    DetailsController,
    HomeController,
    PaymentController,
    SearchController,
    SelectionController,
    TourController,
    UserControllerResource,
    UserController
};
use App\Http\Controllers\Client\Tour\TourController as ClientTourController;
use App\Http\Controllers\Client\Tour\{HotelController, GuideController};
use Illuminate\Support\Facades\Route;

Route::get('/ammemedov', function (){
    $place = \App\Models\Place::query()->findOrFail(9);
   \App\Events\OrderPlaces::dispatch($place);
});
Route::resource('nihad', UserControllerResource::class);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::group(['as' => 'home.', 'prefix' => 'home'], function () {
    Route::get('/place-details/{id}', [DetailsController::class, 'place'])->name('place');
    Route::get('/property-{id}-details', [DetailsController::class, 'property'])->name('property');
    Route::get('/tour-{id}-detalis', [DetailsController::class, 'tourDetails'])->name('tourDetails');
    Route::get('/join-tour/{id}', [TourController::class, 'tourJoin'])->name('tourJoin');
    Route::group(['as' => 'comments.', 'prefix' => 'comments'], function () {
        Route::post("/place/{id}", [CommentsController::class, 'placeComment'])->name('placeComment');
        Route::post("/property/{id}", [CommentsController::class, 'propertyComment'])->name('propertyComment');
        Route::post("/guide/{id}", [CommentsController::class, 'guideComment'])->name('guideComment');
        Route::post("/blog/{id}", [CommentsController::class, 'blogComment'])->name('blogComment');
    });
    Route::group(['as' => 'search.', 'prefix' => "search"], function () {
        Route::get('/place', [SearchController::class, 'searchMain'])->name('searchMain');
        Route::get('/property', [SearchController::class, 'searchProperty'])->name('searchProperty');
        Route::get('/guide', [SearchController::class, 'searchGuide'])->name('searchGuide');
    });
    Route::get('/guide-{id}-details', [DetailsController::class, 'guide'])->name('guide');
});
Route::group(['as' => 'about.', 'prefix' => 'about'], function () {
    Route::get('/about', [AboutController::class, 'index'])->name('aboutPage');
});
Route::group(['as' => 'payment.', 'prefix' => 'payment'], function () {
    Route::get('/{id}/property', [PaymentController::class, 'index'])->name("index");
    Route::get('/{id}/guide', [PaymentController::class, 'guide'])->name("guide");
    Route::post('/{id}/guidePay', [PaymentController::class, 'guidePay'])->name("guidePay");
    Route::post('/{id}/property/book', [PaymentController::class, 'propertyBook'])->name("propertyBook");
});
Route::group(['as' => 'blogs.', 'prefix' => 'blogs'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('blogs');
    Route::get("/details/{id}", [BlogController::class, 'details'])->name('blogDetails');
    Route::post('/blogCreate', [BlogController::class, 'create'])->name('create');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
});

Route::group(['as' => 'tourPlan.', 'prefix' => 'tour-plan', 'middleware' => 'host'], function () {

    Route::get('/tourApprove/{id}', [TourController::class, 'tourApprove'])->name('tourApprove');

    Route::post('/add-hotel/{id}', [HotelController::class, 'store'])->name('tourPlacesAdd');
    Route::post('/add-guide/{id}', [GuideController::class, 'store'])->name('tourGuideAdd');
    Route::get('/tour-place-delete/{id}', [TourController::class, 'tourPlaceDelete'])->name('tourPlaceDelete');
    Route::post('/payAndPublish/{tour_id}', [PaymentController::class, 'payAndPublish'])->name('payAndPublish');

    Route::get('/payment/{id}', [PaymentController::class, 'paymentPage'])->name('payment.page');
    Route::post('/payment/{id}', [PaymentController::class, 'paymentStore'])->name('payment.store');
    Route::view('/approved', 'client.tourPlans.tour3')->name('approved');

    Route::get('/', [ClientTourController::class, 'index'])->name('index');
    Route::get('/create', [ClientTourController::class, 'create'])->name('create');
    Route::post('/store', [ClientTourController::class, 'store'])->name('store');
    Route::get('/{tour}/edit', [ClientTourController::class, 'edit'])->name('edit');
    Route::put('/{tour}', [ClientTourController::class, 'update'])->name('update');
    Route::get('/{id}', [ClientTourController::class, 'show'])->name('show');
    Route::delete('/{id}', [ClientTourController::class, 'destroy'])->name('destroy');
});
Route::group(['as' => "user.", 'prefix' => 'user'], function () {
    Route::get('/{id}', [UserController::class, 'index'])->name('page');
    Route::get("/request/{id}", [UserController::class, 'request'])->name('request');
    Route::post('/edit-profile/{id}', [UserController::class, 'editProfile'])->name('editProfile');
    Route::post('/edit-guide/{id}', [UserController::class, 'editGuide'])->name('editGuide');
    Route::post("/to-guide", [UserController::class, 'guide'])->name('guide');
    Route::get('/editBlog/{id}', [UserController::class, 'userBlogEdit'])->name('userBlogEdit');
    Route::get('/edit-page/{id}', [UserController::class, 'editPage'])->name('editPage');
    Route::post('/editedBlog/{id}', [UserController::class, 'editedBlog'])->name('blog.edit');
    Route::get('/delete-blog/{id}', [UserController::class, 'deleteBlog'])->name('deleteBlog');
    Route::get('/host-request/{id}', [UserController::class, 'host'])->name('host');
    Route::get('/tour_edit/{id}', [UserController::class, 'tourEdit'])->name('editTour');
    Route::post('/tourEdit/{id}', [UserController::class, 'tour_edit'])->name('tourEdit');
    Route::get('/tourPage/{id}', [UserController::class, 'tourPage'])->name('userTour');
    Route::get('/deleteTour/{id}', [UserController::class, 'deleteTour'])->name('deleteTour');
});
Route::group(['as' => 'selection.', 'prefix' => 'selection'], function () {
    Route::get('/place-favorites/{id}', [SelectionController::class, 'place'])->name('place');
    Route::get('/placeDelete/{id}', [SelectionController::class, 'deletePlace'])->name('deletePlace');
    Route::get('/property/{id}', [SelectionController::class, 'property'])->name('property');
    Route::get('/propertyDelete/{id}', [SelectionController::class, 'deleteProperty'])->name('deleteProperty');
    Route::get('/guide/{id}', [SelectionController::class, 'guide'])->name('guide');
    Route::get('/guide-delete/{id}', [SelectionController::class, 'guideDelete'])->name('guideDelete');
    Route::get('place', [SelectionController::class, 'placeFav'])->name('placeFav');
    Route::get('property', [SelectionController::class, 'propertyFav'])->name('propertyFav');
    Route::get('guide', [SelectionController::class, 'guideFav'])->name('guideFav');
});


\Illuminate\Support\Facades\Auth::routes();
Route::get('/register/confirm/{id}', [\App\Http\Controllers\Auth\RegisterController::class, 'confirmRegister'])
    ->name('confirmRegister');
