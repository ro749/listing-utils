@include('listing-utils::Plans.plan-line',[
    'description' => $description,
    'percentage' => $percentage,
    'class' => 'plan-line',
    'id' => 'plan-line-'.$id,
])
@push($push)
@if(isset($percentage) && $percentage != 0)
    var value = final_price*{{ $percentage / 100.0 }};
    $('#fill-plan-line-{{ $id }}').set_money(value);
@endif
@endpush