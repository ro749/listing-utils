<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware(['web'])->group(function () {

    Route::get('imagemappro/map', function (Request $request){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_map();
    });
    Route::get('imagemappro/tower', function (Request $request){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_tower_map();
    });
    Route::get('imagemappro/floor', function (Request $request){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_floor_map($request->input('floor'));
    });
    Route::get('imagemappro/unit', function (Request $request){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_unit($request);
    });

    Route::post('sender', function (Request $request){
        $plans = config('overrides.plans');
        $plans = new $plans();
        $senderClass = config('overrides.sender');
        $sender = new $senderClass($plans);
        return $sender->prosses($request);
    });
});




