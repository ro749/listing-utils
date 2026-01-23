<?php
namespace Ro749\ListingUtils\Plans;
use Illuminate\Support\Facades\Log;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\InputType;

use Ro749\SharedUtils\Forms\Field;
class PersonalizedPlanLine
{
    //the text of the first row, is an index from the list of line text
    public string $text;

    public ?Field $percent;
    public ?Field $ammount;

    public function __construct(
        string $text,
        ?Field $percent,
        ?Field $ammount,
    ){
        $this->text = $text;
        $this->percent = $percent;
        $this->ammount = $ammount;
    }

    public function render(string $id, string $key, BaseForm $form = null)
    {
        return view('listing-utils::Plans.personalized-plan-line', [
            'description' => $this->text,
            'percent' => $this->percent,
            'ammount' => $this->ammount,
            'form' => $form,
            'id' => $id.'-'.$key,
            'input_id' => $id.'_'.$key,
            'key'=>$key,
            'push' => 'fill-plan-'.$id,
        ]);
    }

    public function get_fields(BaseForm $form,string $id, string $key,){
        if(!empty($this->percent)){
            $form->fields['per_'.$id.'_'.$key] = $this->percent;
        }
        if(!empty($this->ammount)){
            $form->fields['fill_'.$id.'_'.$key] = $this->ammount;
        }
    }
}