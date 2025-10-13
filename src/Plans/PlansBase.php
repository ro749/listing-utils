<?php

namespace Ro749\ListingUtils\Plans;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class PlansBase
{   
    public string $plans_table;
    public string $lines_table;

    public Plan $default_plan;
    public string $months_tag = '';
    public string $mensuality_tag = '';
    public int $months;
    public function __construct(
        string $plans_table='plans', 
        string $lines_table='planlines',
        string $months_tag = 'Meses',
        string $mensuality_tag = 'Mensualidad',
        Plan $default_plan = new Plan(
            id: 0,
            title: 'Default',
            discounts: 0,
            lines: [],
            price_tag: 'Precio de lista',
            discount_tag: 'Descuento',
            total_tag: 'Total',
            ppm_tag: 'Precio por metro',
            total_on_top: false,
            ppm: false,
            show_base_price: true
        )
    )
    {
        $this->plans_table = $plans_table;
        $this->lines_table = $lines_table;
        $this->default_plan = $default_plan;
        $this->months_tag = $months_tag;
        $this->mensuality_tag = $mensuality_tag;
    }

    public function get_plans_data()
    {
        $plans_db = DB::table($this->plans_table)->get();
        $plans = [];
        
        foreach ($plans_db as $key => $plan) {
            if($plan->final_date != null){
                if(!is_numeric($plan->final_date)){
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
            $new_plan = json_decode(json_encode($this->default_plan));
            $new_plan->title = $plan->title;
            $new_plan->discount = $plan->discount;
            $new_plan->id = $plan->id;
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
        return $matrix;
    }

    //regresa los planes en matrizes de como se van a acompodar
    public function get(): array{
        return $this->get_in_matrix(2);
    }
    
    public static function instance(): PlansBase
    {
        return new (config('overrides.plans'));
    }
}