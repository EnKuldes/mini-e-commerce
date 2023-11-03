<?php

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

/*Route::get('/tes', function () {
    return view('admin.home');
})->name('halaman-tes-get');
Route::get('/tes1', function () {
    return view('admin.home');
})->middleware('auth')->name('halaman-tes-auth');*/

// Auth Routes
Route::get('/login', 'App\Http\Controllers\LoginController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\LoginController@authenticate');
Route::post('/logout', 'App\Http\Controllers\LoginController@logout')->name('logout');
// Reset Password
Route::get('/reset-password', 'App\Http\Controllers\LoginController@showResetPasswordForm')->name('reset-password');
Route::post('/reset-password', 'App\Http\Controllers\LoginController@resetPassword');
// Register
Route::get('/register', 'App\Http\Controllers\LoginController@showRegisterForm')->name('register');
Route::post('/register', 'App\Http\Controllers\LoginController@registerUser');

// App Routes
Route::middleware(['auth'])->group(function () {
    // Redirect After Login
    Route::get('/', function () {
        return redirect(get_first_page());
    })->name('redirect-first-page');

    // Routing ke Halaman Index
    Route::get('/Page/{controller}', function ($controller, Illuminate\Http\Request $request) {
        $className = "App\Http\Controllers\\$controller" . "Controller";

        // Check CLass Exist
        if (!class_exists($className)) {
            abort(404, "CONTROLLER $controller UNDEFINED!");
        }

        $classInstance = new $className();

        // Check Method Exist
        if (!method_exists($classInstance, 'index')) {
            abort(404, "METHOD or ACTION 'index' is UNDEFINED on class $className");
        }
        return $classInstance->index($request);
    })->name('show-index');

    // Routing ke Halaman sesuai dengan Action yang di panggil
    Route::get('/Page/{controller}/{action}', function ($controller, $action, Illuminate\Http\Request $request) {
        $className = "App\Http\Controllers\\$controller" . "Controller";

        // Check CLass Exist
        if (!class_exists($className)) {
            abort(404, "CONTROLLER $controller UNDEFINED!");
        }

        $classInstance = new $className();
        $method = $request->method();
        // Convert Action (_) ke TitleCase
        $actionName = 'page' . str_replace('-', '', ucwords($action, '-'));

        // Check Method Exist
        if (!method_exists($classInstance, $actionName)) {
            abort(404, "METHOD or ACTION '$actionName' is UNDEFINED on class $className");
        }
        return $classInstance->$actionName($request);
    })->name('show-page');

    // Dynamic Routing berdasarkan Class Name
    Route::any('/Request/{controller}/{action}', function ($controller, $action, Illuminate\Http\Request $request) {
        $className = "App\Http\Controllers\\$controller" . "Controller";

        // Check CLass Exist
        if (!class_exists($className)) {
            abort(404, "CONTROLLER $controller UNDEFINED!");
        }

        $classInstance = new $className();
        $method = strtolower($request->method());
        // Convert Action (_) ke TitleCase dan tambahkan jenis metode untuk request nya di depan nya
        $actionName = $method . str_replace('-', '', ucwords($action, '-'));

        // Check Method Exist
        if (!method_exists($classInstance, $actionName)) {
            abort(404, "METHOD or ACTION '$actionName' is UNDEFINED on class $className");
        }

        return $classInstance->$actionName($request);
    })->name('request-any');
});