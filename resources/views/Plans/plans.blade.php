@foreach ($plans as $row)
<div style="display:flex; flex-direction: row; justify-content: space-evenly;">
    @foreach($row as $plan)
    
        @include('listing-utils::Plans.plan', ['plan' => $plan, 'stack' => 'fill-plan-'.$plan->id])

    @endforeach
</div>
@endforeach