@if(!empty($unit))
@push('fill')
var data = @json($unit);
@endpush
@endif
@foreach ($plans as $row)
<div class="plan-row">
    @foreach($row as $plan)
    
        @include('listing-utils::Plans.plan', ['plan' => $plan, 'stack' => 'fill-plan-'.$plan->id])

    @endforeach
</div>
@endforeach