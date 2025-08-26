@include('listing-utils::Plans.plan-line',[
    'description' => $description,
    'percentage' => $percentage,
    'class' => 'plan-line',
    'id' => 'plan-line-'.$id.'-'.$class,
])

@push('plan-fill')
    var value = final_price*{{ $percentage / 100.0 }};
    $('#fill-plan-line-{{ $id }}-{{ $class }}').text('$'+formatNumber(value));
@endpush