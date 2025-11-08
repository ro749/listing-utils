@include('listing-utils::Plans.plan-line',[
    'description' =>  $description,
    'percentage' => $percentage,
    'class' => 'plan-line',
    'id' => 'plan-line-'.$id,
])

@include('listing-utils::Plans.plan-line',[
    'description' => $month_tag,
    'percentage' => 0,
    'class' => 'plan-months',
    'id' => 'plan-months-'.$id,
])

@include('listing-utils::Plans.plan-line',[
    'description' => $mensuality_tag,
    'percentage' => 0,
    'class' => 'plan-mensuality',
    'id' => 'plan-mensuality-'.$id,
])


@push($push)
    var value = final_price*{{ $percentage / 100.0 }};
    $('#fill-plan-line-{{ $id }}').set_money(value);
    console.log('{{$num}}');
    @if(is_numeric($num))
        var months = {{$num}};
    @else
        var months = data['{{$num}}'];
    @endif
    $('#fill-plan-months-{{ $id }}').text(months);
    $('#fill-plan-mensuality-{{ $id }}').set_money(value);
@endpush
