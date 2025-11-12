<?php

use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\DashboardController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\Website\ResourcesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/mailable', function () {
    $user = App\Models\User::find(1);

    return new App\Mail\SendMail('Testing Mail', 'Hello World', $user);
});

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about-us', function () {
    return view('pages.about');
})->name('about_us');
Route::get('/features', function () {
    return view('pages.features');
})->name('features');
Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');
Route::get('/corporate-philosophy', function () {
    return view('pages.corporate_philosophy');
})->name('corporate_philosophy');

Route::get('/services', function () {
    return view('pages.services');
});
Route::get('services/{serviceType}', [ResourcesController::class, 'services'])->name('services_details');
Route::get('/privacy-policy', function () {
    return view('pages.privacy-policy');
})->name('privacy_policy');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact_us');
Route::get('/request-quote', function () {
    return view('pages.quote');
});
Route::get('/training-form', function () {
    return view('pages.training_form');
})->name('training_form');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::group(['prefix' => 'resources'], function () {
    Route::get('view/{type}', [ResourcesController::class, 'index'])->name('public_resource_index');
    Route::get('details/{resource}', [ResourcesController::class, 'show'])->name('public_resource_details');
});
Route::post('submit/contact-form', [ContactController::class, 'submitContactForm'])->name('submit_contact_form');
Route::post('submit/training-form', [ContactController::class, 'submitTrainingForm'])->name('submit_training_form');


Route::post('submit/consultation-form', [ContactController::class, 'submitConsultationForm'])->name('submit_consultation_form');
Route::post('submit/subscription-form', [ContactController::class, 'subscribeToNewsletter'])->name('submit_subscription_form');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::group(['prefix' => 'resources'], function () {
        Route::get('index/{type}', [ResourcesController::class, 'private'])->name('resource_index');
        Route::get('show/{resource}', [ResourcesController::class, 'show'])->name('resource_show');
        Route::get('create/{type}', [ResourcesController::class, 'create'])->name('resource_create');
        Route::post('store', [ResourcesController::class, 'store'])->name('resource_store');
        Route::get('edit/{resource}', [ResourcesController::class, 'edit'])->name('resource_edit');
        Route::put('update/{resource}', [ResourcesController::class, 'update'])->name('resource_update');
        Route::delete('destroy/{resource}', [ResourcesController::class, 'destroy'])->name('resource_delete');

        Route::delete('destroy-media/{media}', [ResourcesController::class, 'destroyMedia'])->name('media_delete');
    });
});

