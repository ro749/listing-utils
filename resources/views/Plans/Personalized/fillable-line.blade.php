<x-personalized-line :element="$element" />
@push('scripts')
<script>
    var is_updating_change = false;
    var is_updating_input = false;
    $('#per_{{ $element->id }}').on('input', function () {
        if(is_updating_input) return;
        var val = Number(Math.floor($(this).get_number() * 100) / 100.0);
        @if(config()->has('listing.plans.personalized_plan.discounts'))
        var value = (val/100.0)*data['{{ config('listing.plans.personalized_plan.discounts') }}'];
        @else
        var value = (val/100.0)*data['price'];
        @endif
        $('#fill_{{ $element->id }}').set_value(value);
        is_updating_input = true;
        $('#per_{{ $element->id }}').data('flag', true);
        $('#fill_{{ $element->id }}').trigger('input');
        changed_personal();
        $('#per_{{ $element->id }}').removeData('flag');
        is_updating_input = false;
    });
    
    $('#fill_{{ $element->id }}').on('input', function () {
        if(is_updating_input) return;
        var val = $(this).get_number();
        var value = (val/data['price'])*100.0;
        $('#per_{{ $element->id }}').set_value(value);
        is_updating_input = true;
        $('#fill_{{ $element->id }}').data('flag', true);
        $('#per_{{ $element->id }}').trigger('input');
        changed_personal();
        $('#fill_{{ $element->id }}').removeData('flag');
        is_updating_input = false;
        
    });
    $('#per_{{ $element->id }}').on('change', function () {
        if(is_updating_change) return;
        is_updating_change = true;
        $('#fill_{{ $element->id }}').trigger('change');
        is_updating_change = false;
    });
    $('#fill_{{ $element->id }}').on('change', function () {
        if(is_updating_change) return;
        is_updating_change = true;
        $('#per_{{ $element->id }}').trigger('change');
        is_updating_change = false;
    });
    

    $('#fill_{{ $element->id }}').set_value(0);
    $('#per_{{ $element->id }}').set_value(0);
    @if($element->id != 'discount')
    $(document).on('personalized_plan_changed', function(event,final_price){
        if($('#per_{{ $element->id }}').data('flag')){
            var percent = $('#per_{{ $element->id }}').get_number();
            $('#fill_{{ $element->id }}').set_value((percent/100.0)*final_price);
        }
        else{
            var number = $('#fill_{{ $element->id }}').get_number();
            $('#per_{{ $element->id }}').set_percent((number/final_price)*100.0);
        }
    });
    @endif
</script>
@endpush