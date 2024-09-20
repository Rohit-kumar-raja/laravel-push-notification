<?php

use App\Models\User;
use App\Notifications\AccountApproved;
use Illuminate\Http\Request;
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

Route::get('/wel', function () {
    return view('welcome');
});

Route::post('subscribe', function (Request $request) {

    $user = User::find(1);
    $user->updatePushSubscription(
        $request->input('endpoint'),
        $request->input('keys.p256dh'),
        $request->input('keys.auth')
    );

    return response()->json(['success' => true]);


    // return view('subscribe');
});

Route::get('sent', function () {
    $user = User::find(1);; // The user who should receive the notification
    $user->notify(new AccountApproved());
    return response()->json(['message' => 'Notification sent successfully.']);
});
