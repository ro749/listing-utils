<?php
namespace Ro749\ListingUtils\Plans;

use Ro749\SharedUtils\Forms\BaseForm;
class PersonalizedPlanLine
{
    //the text of the first row, is an index from the list of line text
    public string $text;
    //the percentage of the plan line from 0 to 100
    public float $min_percentage;
    public float $max_percentage;

    public float $min_money;
    public float $max_money;

    public function __construct(
        string $text,
        float $min_percentage=0,
        float $max_percentage=0,
        float $min_money=0,
        float $max_money=0
    ){
        $this->text = $text;
        $this->min_percentage = $min_percentage;
        $this->max_percentage = $max_percentage;
        $this->min_money = $min_money;
        $this->max_money = $max_money;
    }

    public function render(string $id, int $key, BaseForm $form)
    {
        return view('listing-utils::Plans.personalized-plan-line', [
            'description' => $this->text,
            'min_percentage' => $this->min_percentage,
            'max_percentage' => $this->max_percentage,
            'min_money' => $this->min_money,
            'max_money' => $this->max_money,
            'form' => $form,
            'id' => $id.'-'.$key,
            'key'=>$key,
            'push' => 'fill-plan-'.$id,
        ]);
    }
}