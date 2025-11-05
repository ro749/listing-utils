<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware(['web'])->group(function () {

    Route::get('imagemappro/{imagemappro}/map', function (Request $request,$imagemappro){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_map();
    });
    Route::get('imagemappro/{imagemappro}/tower', function (Request $request,$imagemappro){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_tower_map();
    });
    Route::get('imagemappro/{imagemappro}/floor', function (Request $request,$imagemappro){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_floor_map($request->input('floor'));
    });
    Route::get('imagemappro/{imagemappro}/unit', function (Request $request,$imagemappro){
        $impClass = config('overrides.image_map_pro');
        $imp = new $impClass();
        return $imp->get_unit($request);
    });

    Route::post('sender', function (Request $request){
        $senderClass = config('overrides.sender');
        $sender = new $senderClass();
        return $sender->prosses($request);
    });
});




