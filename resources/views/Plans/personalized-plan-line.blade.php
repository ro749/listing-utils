@php
$field = new \Ro749\SharedUtils\Forms\Field(type: \Ro749\SharedUtils\Forms\InputType::MONEY);
@endphp
<tr id="{{ $input_id }}">
    <td class="right">
        <strong><span id="line-{{ $input_id }}" class="plan-line-desc">{{ $description }}:</span></strong>
    </td>
    <td class="center">
        @if(!empty($percent))
        <x-field name="per_{{ $input_id }}" :form="$form"/>
        @else
        <div id="per_{{ $input_id }}"></div>
        @endif

    </td>
    <td class="left">
        @if(!empty($amount))
        <x-field name="fill_{{ $input_id }}" :form="$form"/>
        @else
        <div id="fill_{{ $input_id }}"></div>
        @endif
    </td>
</tr>


@push('scripts')
<script>
    @if(!empty($percent) && !empty($amount))
    $('#per_{{ $input_id }}').on('input', function () {
        var val = $(this).get_number();
        var value = (val/100.0)*data['price'];
        $('#fill_{{ $input_id }}').set_money(value);
        changed_personal();
    });
    
    $('#fill_{{ $input_id }}').on('input', function () {
        var val = $(this).get_number();
        var value = (val/data['price'])*100.0;
        $('#per_{{ $input_id }}').set_value(value);
        changed_personal();
    });
    @if(isset($min_percent))
    $('#per_{{ $input_id }}').on('change', function () {
        var val = $(this).get_number();
        if(val<{{ $min_percent }}){
            val = {{ $min_percent }};
            $('#per_{{ $input_id }}').set_percent(val);
            var value = (val/100.0)*data['price'];
            $('#fill_{{ $input_id }}').set_value(value);
            changed_personal();
        }
    });
    $('#fill_{{ $input_id }}').on('change', function () {
        var val = $(this).get_number();
        var value = (val/data['price'])*100.0;
        if(value<{{ $min_percent }}){
            value = {{ $min_percent }};
            val = (value/100.0)*data['price'];
            $('#fill_{{ $input_id }}').set_value(val);
            $('#per_{{ $input_id }}').set_value(value);
        }
    });
    @endif

    $('#fill_{{ $input_id }}').set_value(0);
    $('#per_{{ $input_id }}').set_value(0);
    @elseif(!empty($amount))
    $('#fill_{{ $input_id }}').set_value(0);
    $('#fill_{{ $input_id }}').on('input', function () {
        changed_personal();
    });
    @else
    $('#per_{{ $input_id }}').set_value(0);
    $('#per_{{ $input_id }}').on('input', function () {
        changed_personal();
    });
    @endif

</script>
@endpush