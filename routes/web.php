<?php

use App\Http\Controllers\WebScrapperController;
use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Route for using the shortened url
 * */
Route::get('shorturl/{urlKey}', function ($urlKey) {
    $url = ShortURL::where('url_key', $urlKey)->first();
    return redirect()->away($url->destination_url);
});

/**
 * Route Displaying list
 * */
Route::get('/', function () {

    $urls = ShortURL::latest()->get();
    return view('welcome', compact('urls'));
});

/**
 * Route for Insertion
 * */
Route::post('/', function () {

    $builder        = new Builder();
    $shortURLObject = $builder->destinationUrl(request()->url)->make();
    $shortURL       = $shortURLObject->default_short_url;

    return back()->with('success', 'URL shortened successfully. ');

})->name('url.shorten');

/**
 * Route for updating the url
 */
Route::post('{id}', function ($id) {

    $url                  = ShortURL::find($id);
    $url->url_key         = request()->url;
    $url->destination_url = request()->destination;
    $url->save();

    return back()->with('success', 'URL updated successfully. ');
})->name('update');

/**
 * Route for scrapping the urls
 */
Route::get('scrapper', [WebScrapperController::class, 'index']);

