<?php
namespace Ro749\ListingUtils\Plans\Lines;
class MonthsLines
{
    public $component = 'months-lines';
    //if is string, the number is taken from the database, if is int, the number is taken from the variable
    public FillableLine $line;
    public PlanLine $months_line;
    public PlanLine $mensuality_line;
    public string $plan_id = '';
    public function __construct(
        string $text,
        float $percent,
        string|int $num,
        string $month_tag,
        string $mensuality_tag,
        string $id = '',
        array $classes = [],
        string $plan_id = ''
    ){
        $this->line = new FillableLine($text, $percent, $id, $classes, $plan_id);
        $this->months_line = new PlanLine(
            text: $month_tag, 
            amount: $num,
            id: 'months_'.$id, 
            classes: ['months', 'months-'.$id]
        );
        $this->mensuality_line = new PlanLine(
            text: $mensuality_tag, 
            id: 'mensuality_'.$id, 
            classes: ['mensuality', 'mensuality-'.$id]
        );
        $this->plan_id = $plan_id;
    }

    public function to_upper(){
        $this->line->to_upper();
        $this->months_line->to_upper();
        $this->mensuality_line->to_upper();
    }

    public function render()
    {
        return view('listing-utils::Plans.Lines.months-lines');
    }
}