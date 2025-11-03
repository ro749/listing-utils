<?php

namespace Ro749\ListingUtils\Plans;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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
    public function __construct(
        string $plans_table='plans', 
        string $lines_table='planlines',
        string $months_tag = 'Meses',
        string $mensuality_tag = 'Mensualidad',
        string $title_column = 'title',
        string $discount_column = 'discount',

        string $price_tag = 'Precio de lista',
        string $discount_tag = 'Descuento',
        string $total_tag = 'Total',
        string $ppm_tag = 'Precio por metro',
        bool $total_on_top = false,
        bool $ppm = false,
        bool $show_base_price = true
    )
    {
        $this->plans_table = $plans_table;
        $this->lines_table = $lines_table;
        
        $this->months_tag = $months_tag;
        $this->mensuality_tag = $mensuality_tag;
        $this->title_column = $title_column;
        $this->discount_column = $discount_column;

        $this->price_tag = $price_tag;
        $this->discount_tag = $discount_tag;
        $this->total_tag = $total_tag;
        $this->ppm_tag = $ppm_tag;
        $this->total_on_top = $total_on_top;
        $this->ppm = $ppm;
        $this->show_base_price = $show_base_price;

        $this->personalized_plan = config()->has('listing.plans.personalized_plan') ? 
            new Plan(
                id: 'personal',
                title: config('listing.plans.personalized_plan.title', 'Personalizado'),
                discounts: config('listing.plans.personalized_plan.discounts', 0),
                lines: [],
            )
             : null;
    }

    function get_default_plan($id,$title,$discounts){
        return new Plan(
            id: $id,
            title: $title,
            discounts: 0,
            lines: [],
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
            //Log::info('Months until: '.$monthsUntil);
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
                    $new_plan->lines[] = new PlanLine(
                        text: $line->description,
                        percentage: $line->percent
                    );
                }
                
            }
            $plans[] = $new_plan;
        }
        return $plans;
    }

    public function get_in_matrix(int $plans_per_row)
    {
        $plans = $this->get_plans_data();
        $matrix = [];
        $rows = count($plans) / $plans_per_row;
        for ($i=0;$i<$rows;$i++) {
            $row = [];
            for ($j=0;$j<$plans_per_row;$j++) {
                $row[] = $plans[$i*$plans_per_row+$j];
            }
            $matrix[] = $row;
        }
        if($this->personalized_plan){
            $matrix[] = [$this->personalized_plan];
        }
        return $matrix;
    }

    //regresa los planes en matrizes de como se van a acompodar
    public function get(): array{
        return $this->get_in_matrix(config('listing.plans.plans_per_row'));
    }
    
    public static function instance(): PlansBase
    {
        return new (config('overrides.plans'));
    }
}