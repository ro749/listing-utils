<x-smartForm :form="$form">
@foreach ($plans as $row)
<div class="plan-row">
    @foreach($row as $plan)
        <div class="plan-div" id="plan-div-{{ $plan->id }}">
        {!! $plan->render($form) !!}
        </div>
    @endforeach
</div>
@endforeach
</x-smartForm>
@if(isset($personal_plan))
@push('scripts')
<script>
    
    
    $(document).ready(function(){
        @foreach($personal_plan->toArray() as $key=>$value)
        @if(substr($key, 0, 4) == 'fill')
        $('#{{ $key }}').set_money({{ $value }});
        $('#{{ $key }}').trigger('input');
        $('#{{ $key }}').prop('disabled', true);
        @endif
        $('#per_0').prop('disabled', true);
        $('#per_1').prop('disabled', true);
        @endforeach
        changed_personal();
    });
</script>
@endpush
@endif