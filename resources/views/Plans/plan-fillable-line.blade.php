@include('listing-utils::Plans.plan-line',[
    'description' => $description,
    'percentage' => $percentage,
    'class' => 'plan-line',
    'id' => 'plan-line-'.$id,
])
@push($push)
    var value = final_price*{{ $percentage / 100.0 }};
    $('#fill-plan-line-{{ $id }}').text('$'+formatNumber(value));
@endpush