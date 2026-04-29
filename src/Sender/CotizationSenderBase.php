<?php

namespace Ro749\ListingUtils\Sender;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ro749\ListingUtils\Sender\Cotization;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\Field;
use Ro749\SharedUtils\Forms\CopyField;
use Ro749\SharedUtils\Forms\InputType;
use Ro749\SharedUtils\Forms\FormField;
use Ro749\ListingUtils\Plans\PlansBase;
use Symfony\Component\Mailer\Test\Constraint\EmailCount;
class CotizationSenderBase extends BaseForm
{
    public string $mail_class = '';
    public $client;
    public bool $personalized_plan = false;
    
    public function __construct(PlansBase $plans)
    {
        $this->mail_class = config('listing.cotization_mail_class','App\Mail\CotizationMail');
        $client_id = session()->get('client_id');
        $this->client = (config('overrides.models.Client'))::where('id', $client_id)->first();
        $this->model_class = config('overrides.models.Quotation');
        $this->fields['unit'] = new Field(type: InputType::NUMBER);
        $this->fields['medium'] = new Field(type: InputType::NUMBER);
        $this->user = 'asesor';
        $this->guard = 'asesor';
        $this->fields['quoted_price'] = new CopyField(
            model_class: config('overrides.models.Unit'), 
            column: 'price',
            id: 'unit'
        );
        if(!empty($plans->form)){
            $this->fields['personal_plans'] = new FormField(form: $plans->form,owner_column: 'quotation');
        }
    }

    public function before_process(array &$data){
        $data['client'] = $this->client->id;
    }

    public function after_process($model){
        switch($model->medium){
            case CotizationMedium::LINK->value:
                return route('client-view', ['id' => $model->id]);
            case CotizationMedium::WHATSAPP->value:
                return route('client-view', ['id' => $model->id]);
            case CotizationMedium::MAIL->value:
                $mail_class = $this->mail_class;
                $mail = new $mail_class(
                    unit: $model->unit,
                    link: route('client-view', ['id' => $model->id])
                );
                Mail::to($this->client->mail)->send($mail);
                return;
        }
    }

    function get_id(){
        return class_basename($this);
    }

    function render(){
        $data = ['sender' => $this];
        if(!empty($this->fields['personal_plans']->form)){
            $data['form'] = $this->fields['personal_plans']->form;
        }
        return view(
            config('overrides.views.sender-buttons'),
            $data
        );
    }

    public static function instance(): CotizationSenderBase
    {
        return new (config('overrides.sender'));
    }
}