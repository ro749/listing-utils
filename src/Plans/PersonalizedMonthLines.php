<?php
namespace Ro749\ListingUtils\Plans;

use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\InputType;
use Ro749\SharedUtils\Forms\Field;
use Illuminate\Support\Facades\Log;
class PersonalizedMonthLines
{
    //the text of the first row, is an index from the list of line text
    public string $text;
    //the percentage of the plan line from 0 to 100
    public InputType $type = InputType::MONEY;
    public float $min_percentage;
    public float $max_percentage;

    public float $min_money;
    public float $max_money;

    public string|int|null $num;
    public string $month_tag;
    public string $mensuality_tag;
    public ?Field $percent;
    public ?Field $amount;
    public ?Field $months;

    public function __construct(
        string $text,
        string|int|null $num,
        string $month_tag,
        string $mensuality_tag,
        ?Field $percent = null,
        ?Field $amount = null,
        ?Field $months = null,
        float $min_percentage=0,
        float $max_percentage=0,
        float $min_money=0,
        float $max_money=0,
    ){
        $this->text = $text;
        $this->num = $num;
        $this->month_tag = $month_tag;
        $this->mensuality_tag = $mensuality_tag;
        $this->min_percentage = $min_percentage;
        $this->max_percentage = $max_percentage;
        $this->min_money = $min_money;
        $this->max_money = $max_money;
        $this->percent = $percent;
        $this->amount = $amount;
        $this->months = $months;
    }

    public function render(string $id, string $key, BaseForm $form = null)
    {
        return view('listing-utils::Plans.personalized-months-lines', [
            'description' => $this->text,
            'type' => $this->type,
            'percent' => $this->percent,
            'amount' => $this->amount,
            'months' => $this->months,
            'min_percent' => $this->min_percentage,
            'max_percent' => $this->max_percentage,
            'min_money' => $this->min_money,
            'max_money' => $this->max_money,
            'form' => $form,
            'id' => $id.'-'.$key,
            'input_id' => $id.'_'.$key,
            'key'=>$key,
            'push' => 'fill-plan-'.$id,
            'month_tag' => $this->month_tag,
            'mensuality_tag' => $this->mensuality_tag,
            'num' => $this->num,
        ]);
    }
}