<?php

namespace Ro749\ListingUtils\Sender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class Cotization{
    public string $unit;

    public CotizationMedium $medium;

    public function __construct(
        string $unit,
        CotizationMedium $medium
    ){
        $this->unit = $unit;
        $this->medium = $medium;
    }

    public function save(){
        $cotization = DB::table('cotizations')
            ->where('unit', $this->unit)
            ->where('client',session('client_id'))
            ->first();
        if($cotization){
            return $cotization->id;
        }
        do {
            $id = rand(10000000, 99999999); // genera 8 números aleatorios
        } while (
            DB::table('cotizations')->where('id', $id)->exists()
        );
        $unit = DB::table('units')->where('id','=', $this->unit)->first();
        DB::table('cotizations')->insert([
            'id' => $id,
            'unit' => $this->unit,
            'client' => session('client_id'),
            'asesor' => Auth::guard('asesor')->user()->id,
            'medium' => $this->medium->value,
            'quoted_price'=>$unit->price
        ]);
        return $id;
    }
}