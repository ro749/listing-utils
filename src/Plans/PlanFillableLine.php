<?php
namespace Ro749\ListingUtils\Plans;
class PlanFillableLine
{
    //the text of the first row, is an index from the list of line text
    public string $text;
    //the percentage of the plan line from 0 to 100
    public float $percentage;

    public function __construct(
        string $text,
        float $percentage
    ){
        $this->text = $text;
        $this->percentage = $percentage;
    }

    public function render(string $id, int $key)
    {
        return view('listing-utils::Plans.plan-fillable-line', [
            'description' => $this->text,
            'percentage' => $this->percentage,
            'id' => $id.'-'.$key,
            'push' => 'fill-plan-'.$id,
        ]);
    }
}