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
    public ?Field $amount;

    public function __construct(
        string $text,
        ?Field $percent = null,
        ?Field $amount = null,
    ){
        $this->text = $text;
        $this->percent = $percent;
        $this->amount = $amount;
    }

    public function render(string $id, string $key, BaseForm $form = null,$personal_plan)
    {
        return view('listing-utils::Plans.personalized-plan-line', [
            'description' => $this->text,
            'percent' => $this->percent,
            'amount' => $this->amount,
            'form' => $form,
            'id' => $id.'-'.$key,
            'input_id' => $id.'_'.$key,
            'key'=>$key,
            'push' => 'fill-plan-'.$id,
            'personal_plan' => $personal_plan
        ]);
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