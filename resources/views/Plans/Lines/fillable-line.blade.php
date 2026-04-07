@php
    $modified = clone $element;
    $modified->percent = $element->percent . '%';
    $modified->amount = '';
@endphp

<x-plan-line :element="$modified" />

@push($element->plan_id)
    var value = final_price*{{ $element->percent / 100.0 }};
    $('#fill_{{ $element->id }}').set_money(value);
@endpush