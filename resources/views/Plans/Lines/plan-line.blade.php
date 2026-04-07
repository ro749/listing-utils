<tr id="{{ $element->id }}" class="plan-line @foreach($element->classes as $class) {{ $class }} @endforeach">
    <td class="right">
        <strong>
            <span id="desc-{{ $element->id }}" class="plan-line-desc @foreach($element->classes as $class) desc-{{ $class }} @endforeach">
                {{ $element->text }}:
            </span>
        </strong>
    </td>
    <td class="center">
        <span id="per_{{ $element->id }}" class="plan-line-per @foreach($element->classes as $class) per-{{ $class }} @endforeach">
            {{ $element->percent }}
        </span>
    </td>
    <td class="left">
        <span id="fill_{{ $element->id }}" class="plan-line-fill @foreach($element->classes as $class) fill-{{ $class }} @endforeach">
            {{ $element->amount }}
        </span>
    </td>
</tr>