@php
if(empty($form)){
    $form = null;
}
@endphp
<x-smartForm :form="$form">
@foreach ($plans as $row)
<div class="plan-row">
    @foreach($row as $plan)
        <div class="plan-div" id="plan-div-{{ $plan->id }}">
        {!! $plan->render($form,$personal_plan) !!}
        </div>
    @endforeach
</div>
@endforeach
</x-smartForm>

@if($personal_plan !== null)
    @push('fill')
        @foreach ($personal_plan as $key=>$value)
            @if($key != 'id' && $key != 'quotation' && $key != 'created_at' && $key != 'updated_at')
            @if(!empty($value) && $value != '0.00')
            $('#{{ $key }}').set_value('{{ $value }}');
            $('#{{ $key }}').prop('disabled', true);
            $('#{{ str_replace('fill_','per_', $key) }}').prop('disabled', true);
            @else
            $('#{{ $key }}').parent().parent().parent().hide();
            @endif
            @endif
        @endforeach
        changed_personal();
    @endpush
@elseif(empty($sender))
@push("scripts")
<script>
    //$('#plan-div-personalized').hide();
</script>
@endpush
@endif