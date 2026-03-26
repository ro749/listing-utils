<?php

namespace Ro749\ListingUtils\Plans;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\Field;
use Ro749\SharedUtils\Forms\InputType;
use Illuminate\Support\Facades\Log;
use Ro749\ListingUtils\Plans\PlanFillableLine;
class PlansBase
{   
    public string $plans_table;
    public string $lines_table;
    public string $months_tag = '';
    public string $mensuality_tag = '';
    public int $months;
    public string $title_column = 'title';
    public string $discount_column = 'discount';
    public string $price_tag = 'Precio de lista';
    public string $discount_tag = 'Descuento';

    public string $total_tag = 'Total';
    public string $ppm_tag = 'Precio por metro';
    public bool $total_on_top = false;
    public bool $ppm = false;
    public bool $show_base_price = true;
    public ?Plan $personalized_plan = null;

    public ?BaseForm $form = null;
    public $plans = [];

    public function __construct(
        string $title_column = 'title',
        string $discount_column = 'discount',
        string $ppm_tag = 'Precio por metro',
        bool $ppm = false,
    )
    {
        $this->plans_table = config('listing.plans.table','plans');
        $this->lines_table = config('listing.plans.lines_table','planlines');
        
        $this->months_tag = config('listing.plans.months_tag','MESES');
        $this->mensuality_tag = config('listing.plans.mensuality_tag','MENSUALIDAD');
        $this->title_column = $title_column;
        $this->discount_column = $discount_column;

        $this->price_tag = config('listing.plans.price_tag','Precio de lista');
        $this->discount_tag = config('listing.plans.discount_tag','Descuento');
        $this->total_tag = config('listing.plans.total_tag','Total');
        $this->ppm_tag = $ppm_tag;
        $this->total_on_top = config('listing.plans.total_on_top',false);
        $this->ppm = $ppm;
        $this->show_base_price = config('listing.plans.show_base_price',true);

        

        if(config()->has('listing.plans.personalized_plan')){
            
            $lines = config('listing.plans.personalized_plan.lines', []);
            $this->form = new BaseForm();
            foreach ($lines as $key => $line) {
                if(empty($line['editable'])){
                    if(!empty($line['months'])){
                        $monthsUntil = config('listing.plans.personalized_plan.final_date');
                        if(str_contains($monthsUntil,'-')){
                            $targetDate = Carbon::parse(config('listing.plans.personalized_plan.final_date'));
                            $now = Carbon::now();
                            $monthsUntil = $now->diffInMonths($targetDate);
                        }
                        $lines[$key] = new PlanMonthsLines(
                            text: $line['text'],
                            percentage: 0,
                            num: $monthsUntil,
                            month_tag: $this->months_tag,
                            mensuality_tag: $this->mensuality_tag,
                        );
                    }
                    else{
                        $lines[$key] = new PlanFillableLine(text: $line['text'], percentage: 0);
                    }
                }
                else{
                    $this->form->fields['fill_personal_'.$key] = new Field(type: InputType::MONEY);
                    if(!empty($line['months'])){
                        $monthsUntil = config('listing.plans.personalized_plan.final_date');
                        if(str_contains($monthsUntil,'-')){
                            $targetDate = Carbon::parse(config('listing.plans.personalized_plan.final_date'));
                            $now = Carbon::now();
                            $monthsUntil = $now->diffInMonths($targetDate);
                        }
                        $lines[$key] = new PersonalizedMonthLines(
                            text: $line['text'],
                            num: $monthsUntil,
                            month_tag: $this->months_tag,
                            mensuality_tag: $this->mensuality_tag,
                            percent: new Field(type: InputType::PERCENTAGE),
                            amount: $this->form->fields['fill_personal_'.$key],
                        );
                    }
                    else{
                        $lines[$key] = new PersonalizedPlanLine(
                            text: $line['text'],
                            percent:  new Field(type: InputType::PERCENTAGE),
                            amount: $this->form->fields['fill_personal_'.$key],
                        );
                        if(isset($line['min_percentage'])){
                            $lines[$key]->percent->min = $line['min_percentage'];
                        }
                    }
                }
            }
            $this->personalized_plan = $this->get_default_plan(
                id: 'personal',
                title: config('listing.plans.personalized_plan.title', 'Personalizado'),
                discount: config('listing.plans.personalized_plan.discounts', 0),
                personal: true
            );
            $this->personalized_plan->lines = $lines;
        }

        $this->plans = $this->get();
    }

    

    function get_default_plan($id,$title,$discount,$personal=false){
        $top_lines = [];
        $bottom_lines = [];
        if($this->show_base_price){
            $top_lines[] = new PlanLine(
                text: $this->price_tag, 
                fillable_class: 'base-price',
                fillable_id: 'base-price-'.$id
            );
        }
        if(is_numeric($discount) && $discount != 0){
            $top_lines[] = new PlanLine(
                text: $this->discount_tag, 
                percentage: $discount,
                fillable_class: 'discount',
                fillable_id: 'discount-'.$id
            );
        }
        if($this->total_on_top){
            $top_lines[] = new PlanLine(
                text: $this->total_tag, 
                fillable_class: 'total-price',
                fillable_id: 'total-price-'.$id
            );
            if($this->ppm){
                $top_lines[] = new PlanLine(
                    text: $this->total_tag, 
                    fillable_class: 'ppm-price',
                    fillable_id: 'ppm-price-'.$id
                );
            }
        }
        else{
            $bottom_lines[] = new PlanLine(
                text: $this->total_tag, 
                fillable_class: 'total-price',
                fillable_id: 'total-price-'.$id
            );
            if($this->ppm){
                $bottom_lines[] = new PlanLine(
                    text: $this->total_tag, 
                    fillable_class: 'ppm-price',
                    fillable_id: 'ppm-price-'.$id
                );
            }
        }

        if($personal){
            return new PersonalizedPlan(
                id: $id,
                title: $title,
                discount: $discount,
                top_lines: $top_lines,
                lines: [],
                bottom_lines: $bottom_lines,
                show_base_price: $this->show_base_price,
                price_tag: $this->price_tag,
                total_on_top: $this->total_on_top,
                total_tag: $this->total_tag,
            );
        }
        return new Plan(
            id: $id,
            title: $title,
            discount: $discount,
            top_lines: $top_lines,
            lines: [],
            bottom_lines: $bottom_lines,
            show_base_price: $this->show_base_price,
            price_tag: $this->price_tag,
            total_on_top: $this->total_on_top,
            total_tag: $this->total_tag,
        );
    }

    public function get_plans_data()
    {
        
        $plans_db = DB::table($this->plans_table)->get();
        $plans = [];
        
        foreach ($plans_db as $key => $plan) {
            if($plan->final_date != null){
                if(!is_numeric($plan->final_date) && strtotime($plan->final_date) !== false){
                    $targetDate = Carbon::parse($plan->final_date);
                    $now = Carbon::now();
                    $monthsUntil = $now->diffInMonths($targetDate);
                }
                else{
                    $monthsUntil = $plan->final_date;
                }
            }
            else{
                $monthsUntil = 0;
            }
            $new_plan = $this->get_default_plan(
                $plan->id, 
                $plan->{$this->title_column}, 
                $plan->{$this->discount_column}
            );
            $lines = DB::table($this->lines_table)
                ->where('plan','=', $plan->id)
                ->get();
            foreach ($lines as $line) {
                if($line->months != 0){
                    $new_plan->lines[] = new PlanMonthsLines(
                        text: $line->description,
                        percentage: $line->percent,
                        num: $monthsUntil,
                        month_tag: $this->months_tag,
                        mensuality_tag: $this->mensuality_tag
                    );
                }
                else{
                    $new_plan->lines[] = new PlanFillableLine(
                        text: $line->description,
                        percentage: $line->percent
                    );
                }
                
            }
            $plans[] = $new_plan;
        }
        return $plans;
    }

    public function get_in_matrix(int $plans_per_row = 2,bool $needs_personal = true)
    {
        $plans = $this->get_plans_data();
        $matrix = [];
        $rows = count($plans) / $plans_per_row;
        for ($i=0;$i<$rows;$i++) {
            $row = [];
            for ($j=0;$j<$plans_per_row;$j++) {
                if($i*$plans_per_row+$j >= count($plans)){
                    break;
                }
                $row[] = $plans[$i*$plans_per_row+$j];
            }
            $matrix[] = $row;
        }
        if($this->personalized_plan && $needs_personal){

            $matrix[] = [$this->personalized_plan];
        }
        return $matrix;
    }

    //regresa los planes en matrizes de como se van a acompodar
    public function get(bool $needs_personal = true): array{
        return $this->get_in_matrix(config('listing.plans.plans_per_row',2),$needs_personal);
    }

    public function add_field(
        string $name,
        Field $field
    )
    {
        if($this->form == null){
            $this->form = new BaseForm();
        }
        $this->form->fields[$name] = $field;
        return $this->form->fields[$name];
    }
    
    public function render($personal_plan = null){
        return view(config('overrides.views.plans'), [
            'plans' => $this->plans,
            'form'=>$this->form,
            'personal_plan'=>$personal_plan,
        ]);
    }
    public static function instance(): PlansBase
    {
        $plans = new (config('overrides.plans'));
        return $plans;
    }
}