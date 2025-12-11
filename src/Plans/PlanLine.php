<?php
namespace Ro749\ListingUtils\Plans;
class PlanLine
{
    //the text of the first row, is an index from the list of line text
    public string $text;
    //the percentage of the plan line from 0 to 100
    public ?float $percentage;
    public string $fillable_class;

    public string $fillable_id;

    public function __construct(
        string $text,
        string $fillable_class,
        ?float $percentage = null,
        string $fillable_id=''
    ){
        $this->text = $text;
        $this->percentage = $percentage;
        $this->fillable_class = $fillable_class;
        $this->fillable_id = $fillable_id;
    }

    public function render(string $id, string $key)
    {
        return view('listing-utils::Plans.plan-line', [
            'description' => $this->text,
            'percentage' => $this->percentage,
            'id' => $this->fillable_id,
            'class' => $this->fillable_class
        ]);
    }
}