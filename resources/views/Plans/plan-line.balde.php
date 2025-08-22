<tr id="{{ $plan->id }}" class="plan-line {{ $plan->class }}">
    <td align="right">
        <strong><span id="line-{{ $plan->id }}" class="plan-line-desc line-{{ $plan->class }}">{{ $plan->description }}:</span></strong>
    </td>
    <td align="center">
        @if($plan->percentage != 0)
            <span id="per-{{ $plan->id }}" class="plan-line-per per-{{ $plan->class }}">{{ $plan->percentage }}%</span>
        @endif 
    </td>
    <td align="left">
        <span id="fill-{{ $plan->id }}" class="plan-line-fill fill-{{ $plan->class }}">
        {{ $plan->percentage }}</span>
    </td>
</tr>