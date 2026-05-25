<?php
namespace Ro749\ListingUtils\Plans\Personalized;

use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\ListingUtils\Plans\Lines\PlanLine as StaticPlanLine;
use Ro749\ListingUtils\Plans\Lines\FillableLine as StaticFillableLine;
use Ro749\SharedUtils\Forms\InputType;
use Ro749\SharedUtils\Forms\Field;
use Illuminate\Support\Facades\Log;
class MonthLines
{
    public $component = 'personalized-months-lines';
    public FillableLine|StaticFillableLine $line;
    //depending if the months are editable or not
    public PlanLine|StaticPlanLine $months_line;
    public StaticPlanLine $mensuality_line;

    //if 0 is going to be an input, if not and num is static, if string, is from that column of the unit from the db
    public int|string $num_of_months;
    public string $plan_id;

    public function __construct(
        BaseForm $form,
        string $text,
        string $month_tag,
        string $mensuality_tag,
        //if 0 is going to be an input
        int|string $num_of_months = 0,
        string $id = '',
        array $classes = [],
        string $plan_id = '',
    ){
        $this->plan_id = $plan_id;
        $this->num_of_months = $num_of_months;
        $this->line = new FillableLine(
            form: $form,
            text: $text,
            id: $id,
            classes: $classes
        );
        if(empty($num_of_months)){
            $form->fields['fill_months_'.$id] = new Field(type: InputType::NUMBER,min: 0);
            $this->months_line = new PlanLine(
                text: $month_tag,
                amount: $form->fields['fill_months_'.$id],
                id: 'months_'.$id,
                classes: ['months', 'months-'.$id]
            );
        }
        else{
            $this->months_line = new StaticPlanLine(
                text: $month_tag,
                id: 'months_'.$id,
                classes: ['months', 'months-'.$id]
            );
        }
        $this->mensuality_line = new StaticPlanLine(
            text: $mensuality_tag,
            id: 'mensuality_'.$id,
            classes: ['mensuality', 'mensuality-'.$id]
        );

    }

    public function to_upper(){
        $this->line->to_upper();
        $this->months_line->to_upper();
        $this->mensuality_line->to_upper();
    }

    public function render(string $id, string $key, BaseForm $form = null)
    {
        return view('listing-utils::Plans.personalized-months-lines');
    }
}