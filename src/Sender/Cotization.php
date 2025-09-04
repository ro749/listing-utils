<?php

namespace Ro749\ListingUtils\Sender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class Cotization{
    public string $unit;

    public bool $mail;

    public function __construct(
        string $unit,
        bool $mail
    ){
        $this->unit = $unit;
        $this->mail = $mail;
    }

    public function save(){
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
            'medium' => $this->mail?1:0,
            'quoted_price'=>$unit->price
        ]);
        return $id;
    }
}