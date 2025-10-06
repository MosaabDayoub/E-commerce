<?php

use App\Events\OrderCreated;
use Illuminate\Support\Facades\Route;
use App\Models\Order;

Route::get('/', function () {
    return view('welcome');
});



/* Route::get('test', function () {

    event(new OrderCreated(new Order()));
}); */




