<?php
namespace Ro749\ListingUtils\Plans;
use Illuminate\Support\Facades\Log;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\InputType;
class PersonalizedPlanLine
{
    //the text of the first row, is an index from the list of line text
    public string $text;

    public InputType $type = InputType::MONEY;
    //the percentage of the plan line from 0 to 100
    public float $min_percentage;
    public float $max_percentage;

    public float $min_money;
    public float $max_money;

    public bool $percent = true;
    public bool $amount = true;

    public function __construct(
        string $text,
        InputType $type = InputType::MONEY,
        bool $percent = true,
        bool $amount = true,
        float $min_percentage=0,
        float $max_percentage=0,
        float $min_money=0,
        float $max_money=0
    ){
        $this->text = $text;
        $this->type = $type;
        $this->percent = $percent;
        $this->amount = $amount;
        $this->min_percentage = $min_percentage;
        $this->max_percentage = $max_percentage;
        $this->min_money = $min_money;
        $this->max_money = $max_money;
    }

    public function render(string $id, string $key, BaseForm $form = null)
    {
        return view('listing-utils::Plans.personalized-plan-line', [
            'description' => $this->text,
            'type' => $this->type,
            'percent' => $this->percent,
            'amount' => $this->amount,
            'min_percent' => $this->min_percentage,
            'max_percent' => $this->max_percentage,
            'min_money' => $this->min_money,
            'max_money' => $this->max_money,
            'form' => $form,
            'id' => $id.'-'.$key,
            'input_id' => $id.'_'.$key,
            'key'=>$key,
            'push' => 'fill-plan-'.$id,
        ]);


    }
}