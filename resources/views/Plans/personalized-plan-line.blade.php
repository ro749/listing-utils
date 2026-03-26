<tr id="{{ $input_id }}">
    <td class="right">
        <strong><span id="line-{{ $input_id }}" class="plan-line-desc">{{ $description }}:</span></strong>
    </td>
    <td class="center">
        @if(!empty($percent))
            @if(!empty($form) && !empty($form->fields[$key]))
        <x-field name="per_{{ $input_id }}" :form="$form"/>
            @else
        <x-field name="per_{{ $input_id }}" class="{{ $key }}" :field="$percent"/>
            @endif
        @else
        <div id="per_{{ $input_id }}"></div>
        @endif

    </td>
    <td class="left">
        @if(!empty($amount))
            @if(!empty($form) && !empty($form->fields[$key]))
        <x-field name="fill_{{ $input_id }}" :form="$form"/>
            @else
        <x-field name="fill_{{ $input_id }}" class="{{ $key }}" :field="$amount"/>
            @endif
        @else
        <div id="fill_{{ $input_id }}"></div>
        @endif
    </td>
</tr>


@push('scripts')
<script>
    var is_updating_change = false;
    var is_updating_input = false;
    @if(!empty($percent) && !empty($amount))
    $('#per_{{ $input_id }}').on('input', function () {
        if(is_updating_input) return;
        var val = $(this).get_number();
        var value = (val/100.0)*data['price'];
        $('#fill_{{ $input_id }}').set_value(value);
        is_updating_input = true;
        $('#fill_{{ $input_id }}').trigger('input');
        is_updating_input = false;
        changed_personal();
    });
    
    $('#fill_{{ $input_id }}').on('input', function () {
        if(is_updating_input) return;
        var val = $(this).get_number();
        var value = (val/data['price'])*100.0;
        $('#per_{{ $input_id }}').set_value(value);
        is_updating_input = true;
        $('#per_{{ $input_id }}').trigger('input');
        is_updating_input = false;
        changed_personal();
    });
    $('#per_{{ $input_id }}').on('change', function () {
        if(is_updating_change) return;
        is_updating_change = true;
        $('#fill_{{ $input_id }}').trigger('change');
        is_updating_change = false;
    });
    $('#fill_{{ $input_id }}').on('change', function () {
        if(is_updating_change) return;
        is_updating_change = true;
        $('#per_{{ $input_id }}').trigger('change');
        is_updating_change = false;
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
    @elseif(!empty($percent))
    $('#per_{{ $input_id }}').set_value(0);
    @endif

    @if(!empty($personal_plan) && !empty($personal_plan->{'fill_'.$input_id}))
    $(document).ready(function () {
        $('#fill_{{ $input_id }}').set_value({{ $personal_plan->{'fill_'.$input_id} }});
        $('#fill_{{ $input_id }}').trigger('input');
        $('#fill_{{ $input_id }}').prop('disabled', true);
        $('#per_{{ $input_id }}').prop('disabled', true);
    });
    @endif
</script>
@endpush