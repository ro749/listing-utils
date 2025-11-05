<?php

namespace Ro749\ListingUtils\Plans;
use Illuminate\Support\Facades\Log;
class Plan{
    public string $id;
    public string $title;
    public float $discounts;
    public array $lines;
    public string $price_tag;
    public string $discount_tag;
    public string $total_tag;
    public string $ppm_tag;
    public bool $total_on_top;
    public bool $ppm;
    public bool $show_base_price;

    public function __construct(
        string $id,
        string $title,
        //if instead of discount the price is fixed, set the column of the price of this plan
        float|string $discount,
        array $lines,
        string $price_tag = 'Precio de lista',
        string $discount_tag = 'Descuento',
        string $total_tag = 'Total',
        string $ppm_tag = 'Precio por metro',
        bool $total_on_top = false,
        bool $ppm = false,
        bool $show_base_price = true
    ){
        $this->id = $id;
        $this->title = $title;
        $this->discount = $discount;
        $this->lines = $lines;
        $this->price_tag = $price_tag;
        $this->discount_tag = $discount_tag;
        $this->total_tag = $total_tag;
        $this->ppm_tag = $ppm_tag;
        $this->total_on_top = $total_on_top;
        $this->ppm = $ppm;
        $this->show_base_price = $show_base_price;
    }

    public function render(){
        return view('listing-utils::Plans.plan', [
            'plan' => $this, 'stack' => 'fill-plan-'.$this->id
        ]);
    }
}