<tr id="{{ $element->id }}" class="plan-line @foreach($element->classes as $class) {{ $class }} @endforeach">
    <td class="right">
        <strong>
            <span id="desc-{{ $element->id }}" class="plan-line-desc @foreach($element->classes as $class) desc-{{ $class }} @endforeach">
                {{ $element->text }}:
            </span>
        </strong>
    </td>
    <td class="center">
        @if(!empty($element->percent))
            @if(!empty($element->form) && !empty($element->form->fields['per_'.$element->id]))
        <x-field name="per_{{ $element->id }}" :form="$form"/>
            @else
        <x-field name="per_{{ $element->id }}" class="@foreach($element->classes as $class) per-{{ $class }} @endforeach" :field="$element->percent"/>
            @endif
        @else
        <div id="per_{{ $element->id }}"></div>
        @endif

    </td>
    <td class="left">
        @if(!empty($element->amount))
            @if(!empty($element->form) && !empty($element->form->fields['fill_'.$element->id ]))
        <x-field name="fill_{{ $element->id }}" :form="$form"/>
            @else
        <x-field name="fill_{{ $element->id }}" class="@foreach($element->classes as $class) fill-{{ $class }} @endforeach" :field="$element->amount"/>
            @endif
        @else
        <div id="fill_{{ $element->id }}"></div>
        @endif
    </td>
</tr>