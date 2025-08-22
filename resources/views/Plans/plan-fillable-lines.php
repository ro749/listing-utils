@include('Plans.plan-line',[
    'description' => $line->text,
    'percentage' => $line->percentage,
    'class' => 'plan-line',
    'id' => 'plan-line-'.$id.'-'.$key,
])

@push('plan-fill')
    var value = final_price*{{ $line->percentage / 100.0 }};
    $('#plan-line-{{ $id }}-{{ $key }}').text(formatNumber(value));
@endpush