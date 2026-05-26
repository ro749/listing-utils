<?php
namespace Ro749\ListingUtils\Plans\Personalized;
use Illuminate\Support\Facades\Log;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\InputType;

use Ro749\SharedUtils\Forms\Field;
class DiscountLine extends FillableLine
{
    public $component = 'personalized-discount-line';

    public function __construct(
        BaseForm $form,
        string $text,
        string $plan_id,
        string $id='',
        array $classes = [],
    ){
        if(empty($id)){
            $id = 'discount_'.$plan_id;
        }
        if(empty($classes)){
            $classes = ['discount_'.$plan_id];
        }
        parent::__construct($form, $text, $plan_id, $id, $classes);
    }

    public function render(string $id, string $key, BaseForm $form = null)
    {
        return view('listing-utils::Plans.Lines.Personalized.discount-line');
    }

    public function to_upper(){
        $this->text = mb_strtoupper($this->text, 'UTF-8');
    }

    public function get_fields(BaseForm $form,string $id, string $key,){
        if(!empty($this->percent)){
            $form->fields['per_'.$id.'_'.$key] = $this->percent;
        }
        if(!empty($this->amount)){
            $form->fields['fill_'.$id.'_'.$key] = $this->amount;
        }
    }
}