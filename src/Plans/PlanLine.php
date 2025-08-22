<?php
namespace Ro749\ListingUtils\Plans;
class PlanLine
{
    //the text of the first row, is an index from the list of line text
    public string $text;
    //the percentage of the plan line from 0 to 100
    public float $percentage;
    public string $data;

    public function __construct(
        string $text,
        float $percentage,
        string $data
    ){
        $this->text = $text;
        $this->percentage = $percentage;
        $this->data = $data;
    }

    public function render(string $id, int $key)
    {
        return view('Plans.plan-fillable-line', [
            'line' => $this,
            'id' => $id,
            'key' => $key,
        ]);
    }
}