<?php
namespace Ro749\ListingUtils\Plans;
class PlanMonthsLines extends PlanFillableLine
{
    public string|int $num;
    public string $month_tag;
    public string $mensuality_tag;
    public function __construct(
        string $text,
        float $percentage,
        string|int $num,
        string $month_tag,
        string $mensuality_tag
    ){
        parent::__construct($text, $percentage);
        $this->num = $num;
        $this->month_tag = $month_tag;
        $this->mensuality_tag = $mensuality_tag;
    }

    public function render(string $id, int $key)
    {
        return view('listing-utils::Plans.months-lines', [
            'description' => $this->text,
            'percentage' => $this->percentage,
            'month_tag' => $this->month_tag,
            'mensuality_tag' => $this->mensuality_tag,
            'num' => $this->num,
            'id' => $id.'-'.$key,
            'push' => 'fill-plan-'.$id,
        ]);
    }
}