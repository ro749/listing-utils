@php
if(empty($form)){
    $form = null;
}
if(empty($personal_plan)){
    $personal_plan = null;
}
@endphp
<x-form :form="$form">
@foreach ($plans as $row)
<div class="plan-row">
    @foreach($row as $plan)
        <div class="plan-div" id="plan-div-{{ $plan->id }}">
        {!! $plan->render($form,$personal_plan) !!}
        </div>
    @endforeach
</div>
@endforeach
</x-form>
@push('fill')
$('.fill-base-price').set_money(data['price']);   
@endpush
@if($personal_plan !== null)
    @push('fill')
        @foreach ($personal_plan as $key=>$value)
            @if($key != 'id' && $key != 'quotation' && $key != 'created_at' && $key != 'updated_at')
            @if(!empty($value) && $value != '0.00')
            $('#{{ $key }}').parent().parent().parent().show();
            $('#{{ $key }}').set_value('{{ $value }}');
            $('#{{ $key }}').prop('disabled', true);
            $('#{{ str_replace('fill_','per_', $key) }}').prop('disabled', true);
            @else
            $('#{{ $key }}').parent().parent().parent().hide();
            $('#mensuality_{{ str_replace('fill_','', $key) }}').hide();
            @endif
            @endif
        @endforeach
    @endpush
    @push('after_fill')
        changed_personal();
    @endpush
@elseif(empty($sender))
@push("scripts")
<script>
    console.log('empty sender');
    $('#plan-div-personalized').hide();
</script>
@endpush
@endif