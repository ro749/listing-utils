<x-fillable-line :element="$element->line" />

<x-plan-line :element="$element->months_line" />

<x-plan-line :element="$element->mensuality_line" />

@push($element->plan_id)
    var value = final_price*{{ $element->line->percent / 100.0 }};
    $('#fill_{{ $element->line->id }}').set_money(value);
    @if(is_numeric($element->months_line->amount))
        var months = {{$element->months_line->amount}};
    @else
        var months = data['{{$element->months_line->amount}}'];
    @endif
    $('#fill_{{ $element->months_line->id }}').text(months);
    $('#fill_{{ $element->mensuality_line->id }}').set_money(value/months);
@endpush
