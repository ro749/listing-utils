<?php

namespace Ro749\ListingUtils\Plans;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Ro749\SharedUtils\Forms\BaseForm;
use Ro749\SharedUtils\Forms\Field;
use Ro749\SharedUtils\Forms\InputType;
use Illuminate\Support\Facades\Log;
use Ro749\ListingUtils\Plans\Lines\PlanLine;
use Ro749\ListingUtils\Plans\Lines\MonthsLines;
use Ro749\ListingUtils\Plans\Lines\FillableLine;
use Ro749\ListingUtils\Plans\Lines\DiscountLine;
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
            Log::info('Personalized plan loaded');
            $this->form = new BaseForm();
            $def_plan = $this->get_default_plan('personalized', '', 0);
            $override_plan = config('listing.plans.personalized_plan.plan_override');
            if($override_plan){
                $this->personalized_plan = new ($override_plan)(
                    id: 'personalized',
                    title: config('listing.plans.personalized_plan.title','Personalizado'),
                    enganche_tag: config('listing.plans.enganche_tag','Enganche'),
                    liquidacion_tag: config('listing.plans.liquidacion_tag','Liquidación a la firma'),
                    form: $this->form,
                    top_lines: $def_plan->top_lines,
                    bottom_lines: $def_plan->bottom_lines,
                );
            }
            else{
                Log::info('Default plan loaded');
                $this->personalized_plan = new PersonalPlan(
                    id: 'personalized',
                    title: config('listing.plans.personalized_plan.title','Personalizado'),
                    enganche_tag: config('listing.plans.enganche_tag','Enganche'),
                    liquidacion_tag: config('listing.plans.liquidacion_tag','Liquidación a la firma'),
                    form: $this->form,
                    top_lines: $def_plan->top_lines,
                    bottom_lines: $def_plan->bottom_lines,
                );
            }
            
        }

        $this->plans = $this->get();
    }

    

    function get_default_plan($id,$title,$discount,$personal=false){
        $top_lines = [];
        $bottom_lines = [];
        if($this->show_base_price){
            $top_lines[] = new PlanLine(
                text: $this->price_tag, 
                id: 'base-price-'.$id,
                classes: ['base-price'],
            );
        }
        if(is_numeric($discount) && $discount != 0){
            $top_lines[] = new DiscountLine(
                text: $this->discount_tag, 
                percent: $discount,
                id: 'discount-'.$id,
                classes: ['discount'],
                plan_id: $id
            );
        }
        if($this->total_on_top){
            $top_lines[] = new PlanLine(
                text: $this->total_tag, 
                id: 'total-price-'.$id,
                classes: ['total-price'],
            );
            if($this->ppm){
                $top_lines[] = new PlanLine(
                    text: $this->total_tag, 
                    id: 'ppm-price-'.$id,
                    classes: ['ppm-price'],
                );
            }
        }
        else{
            $bottom_lines[] = new PlanLine(
                text: $this->total_tag, 
                id: 'total-price-'.$id,
                classes: ['total-price']
            );
            if($this->ppm){
                $bottom_lines[] = new PlanLine(
                    text: $this->total_tag, 
                    id: 'ppm-price-'.$id,
                    classes: ['ppm-price']
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
                if($line->percent == 0) continue;
                if($line->months != 0){
                    $new_plan->lines[] = new MonthsLines(
                        text: $line->description,
                        percent: $line->percent,
                        num: $monthsUntil,
                        month_tag: $this->months_tag,
                        mensuality_tag: $this->mensuality_tag,
                        id: $key.'-'.$line->id,
                        classes: [$key.'-'.$line->id],
                        plan_id: $plan->id
                    );
                }
                else{
                    $new_plan->lines[] = new FillableLine(
                        text: $line->description,
                        percent: $line->percent,
                        id: $key.'-'.$line->id,
                        classes: [$key.'-'.$line->id],
                        plan_id: $plan->id
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
        Log::info('personalized_plan: '.json_encode($this->personalized_plan));
        Log::info('needs_personal: '.($needs_personal?'true':'false'));
        if($this->personalized_plan && $needs_personal){
            $matrix[] = [$this->personalized_plan];
        }
        Log::info('matrix: '.json_encode($matrix));
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
    
    public function render($personal_plan = null,$sender = null){
        return view(config('overrides.views.plans'), [
            'plans' => $this->plans,
            'form'=>$this->form,
            'personal_plan'=>$personal_plan,
            'sender'=>$sender
        ]);
    }
    public static function instance(): PlansBase
    {
        $plans = new (config('overrides.plans'));
        return $plans;
    }
}