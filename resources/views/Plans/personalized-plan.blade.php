<x-smartForm :form="$form">
@include('listing-utils::Plans.plan',[
    'plan' => $plan, 'stack' => $stack, 'form' => $form
])
</x-smartForm>
@push('scripts')
<script>
    function changed_personal(){
        var val0 = $('#fill_0').get_number();
        var val1 = $('#fill_1').get_number();
        if(val0+val1>data['price']){
            val1 = data['price']-val0;
            $('#fill_1').set_money(val1);
            $('#per_1').set_percent(((val1/data['price'])*100.0));
        }
        var final_value = Number(data['price']) - val0 - val1;
        $('#fill-plan-line-personal-2').set_money(final_value);
        $('#per-plan-line-personal-2').set_percent(((final_value/data['price'])*100.0));
    }
</script>
@endpush