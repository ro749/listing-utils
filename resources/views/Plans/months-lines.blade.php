@include('Plans.plan-line',[
    'description' => $line->text,
    'percentage' => $line->percentage,
    'class' => 'plan-line',
    'id' => 'plan-line-'.$id.'-'.$key,
])

@include('Plans.plan-line',[
    'description' => $line->month_tag,
    'percentage' => 0,
    'class' => 'plan-months',
    'id' => 'plan-months-'.$id,
])

@include('Plans.plan-line',[
    'description' => $line->mensuality_tag,
    'percentage' => 0,
    'class' => 'plan-mensuality',
    'id' => 'plan-mensuality-'.$id,
])

<script>
@push('plan-fill')
    var value = final_price*{{ $line->percentage / 100.0 }};
    $('#plan-line-{{ $id }}-{{ $key }}').text(formatNumber(value));
    @if(is_numeric($line->num))
        var months = {{$line->num}};
    @else
        var months = plan['{{$line->num}}'];
    @endif
    $('#plan-months-{{ $id }}').text(months);
    $('#plan-mensuality-{{ $id }}').text(value/months);
@endpush
</script>