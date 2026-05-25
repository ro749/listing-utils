<?php
namespace Ro749\ListingUtils\Plans\Personalized;
use Illuminate\Support\Facades\Log;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\InputType;

use Ro749\SharedUtils\Forms\Field;
class PlanLine
{
    public $component = 'personalized-line';
    //the text of the first row, is an index from the list of line text
    public string $text;

    public ?Field $percent;
    public ?Field $amount;

    //the id of the line, specially used for filling
    public string $id;

    //for ir there are a lot of the same
    public array $classes = [];

    public function __construct(
        string $text,
        string $id='',
        array $classes = [],
        ?Field $percent = null,
        ?Field $amount = null,
    ){
        $this->text = $text;
        $this->id = $id;
        $this->classes = $classes;
        $this->percent = $percent;
        $this->amount = $amount;
    }

    public function render(string $id, string $key, BaseForm $form = null)
    {
        return view('listing-utils::Plans.Lines.personalized-plan-line');
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