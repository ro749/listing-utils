<div>
@include('listing-utils::Plans.plan',[
    'plan' => $plan, 'form' => $form, 'id' => $id
])
</div>
<p style="font-size: .833rem">*Plan sujeto a autorización interna y condiciones de venta</p>
@push('scripts')
<script>
    function changed_personal(){
        
        @if(config()->has('listing.plans.personalized_plan.discounts'))
        var final_price = data['{{ config('listing.plans.personalized_plan.discounts') }}'];
        @else
        var final_price = data['price'];
        @endif
        var discount = $('#fill_discount_personalized').get_number();
        if(discount>0){
            if(!$('#per_discount').data('flag')){
                $('#per_discount').set_percent((discount/final_price)*100.0);
            }
            final_price = final_price - discount;
        }
        $('#fill_total-price-personalized').set_money(final_price);
        $(document).trigger('personalized_plan_changed',[final_price]);
        return;
    }
    $(document).on('personalized_plan_changed', function(event,final_price){
        if($(document).data('no_auto_update_personalized')){
            return;
        }
        if($('#fill_{{ $autofill }}').length){
            var {{ $autofill }} = final_price
            @foreach ($lines_for_fill as $fill_line)
            @if($fill_line != 'discount')
                -$('#fill_{{ $fill_line }}').get_number()      
            @endif    
            @endforeach
            @foreach ($lines_for_fill as $fill_line)
            @endforeach
            ;
            $('#fill_{{ $autofill }}').set_money({{ $autofill }});
            
            $('#per_{{ $autofill }}').set_percent(({{ $autofill }}/final_price)*100.0);
        }
    })
</script>
@push('after_fill')
    changed_personal();
@endpush
@endpush