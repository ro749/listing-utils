<?php
namespace Ro749\ListingUtils\Plans\Personalized;
use Illuminate\Support\Facades\Log;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\InputType;

use Ro749\SharedUtils\Forms\Field;
class FillableLine
{
    public $component = 'personalized-fillable-line';
    //the text of the first row, is an index from the list of line text
    public string $text;
    public ?Field $percent;
    public ?Field $amount;

    //the id of the line, specially used for filling
    public string $id;

    //for ir there are a lot of the same
    public array $classes = [];
    public string $plan_id;

    public function __construct(
        BaseForm $form,
        string $text,
        string $id='',
        array $classes = [],
        string $plan_id = ''
    ){
        $this->text = $text;
        $this->id = $id;
        $this->classes = $classes;
        $this->percent = new Field(type: InputType::PERCENTAGE);
        $form->fields['fill_'.$id] = new Field(type: InputType::MONEY);
        $this->amount = $form->fields['fill_'.$id];
        $this->plan_id = $plan_id;
    }

    public function render(string $id, string $key, BaseForm $form = null)
    {
        return view('listing-utils::Plans.Lines.Personalized.fillable-line');
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