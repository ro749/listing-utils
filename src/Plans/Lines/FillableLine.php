<?php
namespace Ro749\ListingUtils\Plans\Lines;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;
//this lines fills automaticly in base of a percent when the data of a unit is loaded
class FillableLine extends Component
{
    public $component = 'fillable-line';
    //the text of the first row, is an index from the list of line text
    public string $text;
    //the percentage of the plan line from 0 to 100
    public string $percent;
    //the id of the line, specially used for filling
    public string $id;

    //for ir there are a lot of the same
    public array $classes = [];
    public string $plan_id;

    public function __construct(
        string $text,
        float $percent,
        string $id = '',
        array $classes = [],
        string $plan_id = ''
    ){
        $this->text = $text;
        $this->percent = $percent;
        $this->id = $id;
        $this->classes = $classes;
        $this->plan_id = $plan_id;
    }

    public function render()
    {
        return view('listingutils::Plans.Lines.fillable-line');
    }
}