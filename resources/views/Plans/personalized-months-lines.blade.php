@include('listing-utils::Plans.personalized-plan-line',[
    'description' =>  $description,
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
    @if(is_numeric($num))
        var months = {{$num}};
    @else
        var months = data['{{$num}}'];
    @endif
    $('#fill-plan-months-{{ $id }}').text(months);
    $(document).on('personalized_plan_changed', function(){
        var value = $('#fill_personal_{{ $key }}').get_number();
        $('#fill-plan-mensuality-{{ $id }}').set_money(value/months);
    });
@endpush