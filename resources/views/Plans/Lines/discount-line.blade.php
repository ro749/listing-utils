@php
    $modified = clone $element;
    $modified->percent = $element->percent . '%';
    $modified->amount = '';
@endphp

<x-plan-line :element="$modified" />

@push($element->plan_id)
    $('#fill_{{ $element->id }}').set_money(discount);
@endpush