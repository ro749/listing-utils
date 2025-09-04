<?php

namespace Ro749\ListingUtils\Sender;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ro749\ListingUtils\Sender\Cotization;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
class BaseCotizationSender
{
    public string $id = '';
    public string $mail_class = '';
    public $client;
    
    public function __construct(string $id,string $mail_class)
    {
        $this->id = $id;
        $this->mail_class = $mail_class;
        $client_id = session()->get('client_id');
        $this->client = DB::table('clients')->where('id', $client_id)->first();
    }
    public function get_link(Request $request){
        $cotization = new Cotization(
            unit: $request->input('unit'),
            mail: false
        );
        $id = $cotization->save();
        return 'https://wa.me/'.$this->client->phone.'?text='.route('client-view', ['id' => $id]);
    }

    public function get_mail(Request $request){
        $cotization = new Cotization(
            unit: $request->input('unit'),
            mail: true
        );
        $id = $cotization->save();
        $mail_class = $this->mail_class;
        $mail = new $mail_class(
            unit: $request->input('unit'),
            link: route('client-view', ['id' => $id])
        );
        Mail::to($this->client->mail)->send($mail);
    }
}