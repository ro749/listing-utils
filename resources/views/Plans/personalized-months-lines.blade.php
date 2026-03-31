@include('listing-utils::Plans.personalized-plan-line',[
    'description' =>  $description,
    'class' => 'plan-line',
    'id' => 'plan-line-'.$id,
    'amount' => $amount,
    'percent' => $percent
])

@if($num === null)
@include('listing-utils::Plans.personalized-plan-line',[
    'description' => $month_tag,
    'percentage' => 0,
    'percent' => null,
    'amount' => $months,
    'class' => 'plan-months',
    'id' => 'plan-months-'.$id,
    'input_id' => 'plan_months_input_'.$input_id,
])
@else
@include('listing-utils::Plans.plan-line',[
    'description' => $month_tag,
    'percentage' => 0,
    'percent' => null,
    'class' => 'plan-months',
    'id' => 'plan-months-'.$id,
])
@endif

@include('listing-utils::Plans.plan-line',[
    'description' => $mensuality_tag,
    'percentage' => 0,
    'class' => 'plan-mensuality',
    'id' => 'plan-mensuality-'.$id,
])
@push($push)
    @if(is_numeric($num))
        var months = {{$num}};
    @elseif($num !== null)
        var months = data['{{$num}}'];
    @else
        var months = 0;
    @endif

    $('#fill_plan_months_input_{{$input_id}}').on('input', function () {
        changed_personal();
    });

    $('#fill-plan-months-{{ $id }}').text(months);
    $(document).on('personalized_plan_changed', function(){
        var value = $('#fill_personal_{{ $key }}').get_number();
        @if($num === null)
            months = $('#fill_plan_months_input_{{$input_id}}').get_number();
        @endif
        $('#fill-plan-mensuality-{{ $id }}').set_money(value/months);
    });
@endpush