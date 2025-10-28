<?php

namespace Ro749\ListingUtils\Sender;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ro749\ListingUtils\Sender\Cotization;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class CotizationSenderBase
{
    public string $mail_class = '';
    public $client;
    
    public function __construct()
    {
        $this->mail_class = config('listing.cotization_mail_class','App\Mail\CotizationMail');
        $client_id = session()->get('client_id');
        $this->client = DB::table('clients')->where('id', $client_id)->first();
        Log::debug('Client ID: '.$client_id);
    }
    public function get_link(Request $request){
        $cotization = new Cotization(
            unit: $request->input('unit'),
            medium: CotizationMedium::LINK
        );
        $id = $cotization->save();
        return route('client-view', ['id' => $id]);
    }
    public function get_whatsapp(Request $request){
        $cotization = new Cotization(
            unit: $request->input('unit'),
            medium: CotizationMedium::WHATSAPP
        );
        $id = $cotization->save();
        return 'https://wa.me/'.$this->client->phone.'?text='.route('client-view', ['id' => $id]);
    }

    public function get_mail(Request $request){
        $cotization = new Cotization(
            unit: $request->input('unit'),
            medium: CotizationMedium::MAIL
        );
        $id = $cotization->save();
        $mail_class = $this->mail_class;
        $mail = new $mail_class(
            unit: $request->input('unit'),
            link: route('client-view', ['id' => $id])
        );
        Mail::to($this->client->mail)->send($mail);
    }

    function get_id(){
        return class_basename($this);
    }

    public static function instance(): CotizationSenderBase
    {
        return new (config('overrides.sender'));
    }
}