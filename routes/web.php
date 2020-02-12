<?php

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

Route::get('/', function () {
    App::setLocale('PT');

    $socrates = new App\Socrates\Socrates();

    return $socrates->getCitizenData('251195-1448');

//    if ($socrates->validateId('251195-1448')) {
//        return ':)';
//    }
//
//    return 'Elton Pastilha';
});
