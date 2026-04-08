@php
$per_classes = '';
$fill_classes = '';
foreach($element->classes as $class){
$per_classes .= ' per_'.$class;
$fill_classes .= ' fill_'.$class;
}
@endphp
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
        <x-field name="per_{{ $element->id }}" class="{{ $per_classes }}" :form="$form"/>
            @else
        <x-field name="per_{{ $element->id }}" class="{{ $fill_classes }}" :field="$element->percent"/>
            @endif
        @else
        <div id="per_{{ $element->id }}"></div>
        @endif

    </td>
    <td class="left">
        @if(!empty($element->amount))
            @if(!empty($element->form) && !empty($element->form->fields['fill_'.$element->id ]))
        <x-field name="fill_{{ $element->id }}" classes="{{ $per_classes }}" :form="$form"/>
            @else
        <x-field name="fill_{{ $element->id }}" classes="{{ $fill_classes }}" :field="$element->amount"/>
            @endif
        @else
        <div id="fill_{{ $element->id }}"></div>
        @endif
    </td>
</tr>