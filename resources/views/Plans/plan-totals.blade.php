@include('listing-utils::Plans.plan-line',[
    'description' => $plan->total_tag,
    'percentage' => 0,
    'class' => 'total-price',
    'id' => 'total-price-'.$plan->id,
])
@if($plan->ppm)
@include('listing-utils::Plans.plan-line',[
    'description' => $plan->ppm_tag,
    'percentage' => 0,
    'class' => 'ppm-price',
    'id' => 'ppm-price-'.$plan->id,
])
@endif