@php
$field = new \Ro749\SharedUtils\Forms\Field(type: \Ro749\SharedUtils\Forms\InputType::MONEY);
@endphp
<tr id="{{ $id }}">
    <td class="right">
        <strong><span id="line-{{ $id }}" class="plan-line-desc">{{ $description }}:</span></strong>
    </td>
    <td class="center">
        <x-field name="per_{{ $key }}" :form="$form"/>
    </td>
    <td class="left">
        <x-field name="fill_{{ $key }}" :field="$field"/>
    </td>
</tr>


@push('scripts')
<script>
    $('#per_{{ $key }}').on('input', function () {
        console.log('changed percent');
        var val = $(this).get_number();
        var value = (val/100.0)*data['price'];
        $('#fill_{{ $key }}').set_money(value);
        changed_personal();
    });
    
    $('#fill_{{ $key }}').on('input', function () {
        var val = $(this).get_number();
        var value = (val/data['price'])*100.0;
        $('#per_{{ $key }}').set_percent(value);
        changed_personal();
    });
    @if(isset($min_percent))
    $('#per_{{ $key }}').on('change', function () {
        var val = $(this).get_number();
        if(val<{{ $min_percent }}){
            val = {{ $min_percent }};
            $('#per_{{ $key }}').set_percent(val);
            var value = (val/100.0)*data['price'];
            $('#fill_{{ $key }}').set_money(value);
            changed_personal();
        }
    });
    $('#fill_{{ $key }}').on('change', function () {
        var val = $(this).get_number();
        var value = (val/data['price'])*100.0;
        if(value<{{ $min_percent }}){
            value = {{ $min_percent }};
            val = (value/100.0)*data['price'];
            $('#fill_{{ $key }}').set_money(val);
            $('#per_{{ $key }}').set_percent(value);
        }
    });
    @endif

    $('#fill_{{ $key }}').set_money(0);
    $('#per_{{ $key }}').set_percent(0);
</script>
@endpush