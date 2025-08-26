<?php

namespace Ro749\ListingUtils\Plans;

use Illuminate\Support\Facades\DB;

abstract class PlansBase
{   
    public string $plans_table;
    public string $lines_table;

    public Plan $default_plan;

    public function __construct(
        string $plans_table, 
        string $lines_table,
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
    }

    public function get_plans_data()
    {
        $plans_db = DB::table($this->plans_table)->get();
        $plans = [];
        foreach ($plans_db as $key => $plan) {
            $new_plan = json_decode(json_encode($this->default_plan));
            $new_plan->title = $plan->title;
            $new_plan->discount = $plan->discount;
            $new_plan->id = $plan->id;
            $lines = DB::table($this->lines_table)
                ->where('plan','=', $plan->id)
                ->get();
            foreach ($lines as $line) {
                $new_plan->lines[] = new PlanLine(
                    text: $line->description,
                    percentage: $line->percent
                );
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
    abstract function get(): array;
    
}