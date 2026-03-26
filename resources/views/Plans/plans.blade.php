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