<div>
@include('listing-utils::Plans.plan',[
    'plan' => $plan, 'form' => $form, 'id' => $id
])
</div>
<p style="font-size: .833rem">*Plan sujeto a autorización interna y condiciones de venta</p>
@push('scripts')
<script>
    function changed_personal(){
        var final_price = data['price'];
        var discount = $('#fill_discount').get_number();
        if(discount>0){
            if(!$('#per_discount').data('flag')){
                $('#per_discount').set_percent((discount/final_price)*100.0);
            }
            final_price = final_price - discount;
        }
        $('#fill-total-price-personalized').set_money(final_price);

        if($('#fill_{{ $autofill }}').length){
            var {{ $autofill }} = final_price
            @foreach ($lines_for_fill as $fill_line)
                -$('#fill_{{ $fill_line }}').get_number()          
            @endforeach
            ;
            $('#fill_{{ $autofill }}').set_money({{ $autofill }});
            $('#per_{{ $autofill }}').set_percent(({{ $autofill }}/final_price)*100.0);
        }
        $(document).trigger('personalized_plan_changed',[final_price]);
        return;
        
    }
</script>
@endpush