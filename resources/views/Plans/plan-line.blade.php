<tr id="{{ $id }}" class="plan-line {{ $class }}">
    <td class="right">
        <strong><span id="line-{{ $id }}" class="plan-line-desc line-{{ $class }}">{{ $description }}:</span></strong>
    </td>
    <td class="center">
        <span id="per-{{ $id }}" class="plan-line-per per-{{ $class }}">
        @if($percentage != 0)
            {{ $percentage }}%
        @endif 
        </span>
    </td>
    <td class="left">
        <span id="fill-{{ $id }}" class="plan-line-fill fill-{{ $class }}">
        {{ $percentage }}</span>
    </td>
</tr>