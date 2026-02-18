<?php

use Illuminate\Support\Facades\Route;
use App\Events\OrderCreated;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-workflow', function () {

    event(new OrderCreated([
        'order_id' => 501,
        'amount'   => 1000,
        'user_id'  => 5,
    ]));

    return 'Workflow event fired';
});