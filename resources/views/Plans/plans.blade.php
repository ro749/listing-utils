@if(!empty($unit))
@push('fill')
var data = @json($unit);
@endpush
@endif
@foreach ($plans as $row)
<div class="plan-row">
    @foreach($row as $plan)
        <div class="plan-div" id="plan-div-{{ $plan->id }}">
        {!! $plan->render() !!}
        </div>
    @endforeach
</div>
@endforeach
@if(isset($personal_plan))
@push('scripts')
<script>
    var data = @json($unit);
    @foreach($personal_plan->toArray() as $key=>$value)
    @if(substr($key, 0, 3) == 'per')
    $('#{{ $key }}').set_percent({{ $value }});
    $('#{{ $key }}').prop('disabled', true);
    @endif
    @if(substr($key, 0, 4) == 'fill')
    $('#{{ $key }}').set_money({{ $value }});
    $('#{{ $key }}').prop('disabled', true);
    @endif$
    @endforeach
    
    $(document).ready(function(){
        changed_personal();
    });
</script>
@endpush
@endif