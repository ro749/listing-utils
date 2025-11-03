@if(!empty($unit))
@push('fill')
var data = @json($unit);
@endpush
@endif
@foreach ($plans as $row)
<div class="plan-row">
    @foreach($row as $plan)
    
        {!! $plan->render() !!}

    @endforeach
</div>
@endforeach