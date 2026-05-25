<?php
namespace Ro749\ListingUtils\Plans\Lines;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;
//a basic plan line, all is setted from the start or can be filled manually during runtime
class PlanLine extends Component
{
    public $component = 'plan-line';
    //the text of the first row, is an index from the list of line text
    public string $text;
    //almost always a percentage, is the second line
    public string $percent;
    //the third line
    public string $amount;
    //the id of the line, specially used for filling
    public string $id;

    //for ir there are a lot of the same
    public array $classes = [];

    public function __construct(
        string $text = '',
        string $percent = '',
        string $amount = '',
        string $id='',
        array $classes = []
    ){
        $this->text = $text;
        $this->percent = $percent;
        $this->amount = $amount;
        $this->id = $id;
        $this->classes = $classes;
    }

    public function to_upper(){
        $this->text = mb_strtoupper($this->text, 'UTF-8');
    }

    public function render()
    {
        return view('listingutils::Plans.Lines.plan-line');
    }
}