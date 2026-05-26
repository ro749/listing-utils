<?php

namespace Ro749\ListingUtils\Plans;
use Illuminate\Support\Facades\Log;
use Ro749\ListingUtils\Plans\Lines\PlanLine;
use Ro749\SharedUtils\Forms\BaseForm;
class PersonalPlan extends Plan{
    public BaseForm $form;
    public string $autofill = '';
    public array $lines_for_fill = [];
    public function __construct(
        BaseForm $form,
        string $id = 'personal',
        string $title = 'Personalizado',
        float|string $discount = 0,
        array $top_lines = [],
        array $lines = [],
        array $bottom_lines = [],
        string $price_tag = 'Precio de lista',
        string $discount_tag = 'Descuento',
        string $total_tag = 'Total',
        string $ppm_tag = 'Precio por metro',
        bool $total_on_top = false,
        bool $ppm = false,
        bool $show_base_price = true,
        string $enganche_tag = 'Enganche',
        string $plazo_tag = 'Plazo',
        string $liquidacion_tag = 'Liquidación a la firma',
        string $autofill = 'liquidacion',
        array $lines_for_fill = ['enganche', 'plazo'],
    ){
        parent::__construct($id, $title, $discount, $top_lines, $lines, $bottom_lines, $price_tag, $discount_tag, $total_tag, $ppm_tag, $total_on_top, $ppm, $show_base_price);
        $this->form = $form;
        $this->lines = $lines;
        $this->lines[] = new Personalized\DiscountLine($form, $discount_tag, 'discount', $id, ['personalized discount']);
        $this->lines[] = new Personalized\FillableLine($form, $enganche_tag, 'enganche', $id, ['personalized']);    
        $this->lines[] = new Personalized\MonthLines(
            form: $form, 
            text: $plazo_tag,
            month_tag: 'Meses',
            mensuality_tag: 'Mensualidad',
            classes: ['personalized'],
            id: 'plazo',
            plan_id: $id,
        );
        $this->lines[] = new PlanLine(text: $liquidacion_tag, classes: ['personalized'], id: 'liquidacion');
        $this->autofill = $autofill;
        $this->lines_for_fill = $lines_for_fill;
    }

    public function render($form){
        return view('listing-utils::Plans.personalized-plan', [
            'plan' => $this, 
            'id' => $this->id,
            'form' => $form,
            'autofill' => $this->autofill,
            'lines_for_fill' => $this->lines_for_fill
        ]);
    }
}