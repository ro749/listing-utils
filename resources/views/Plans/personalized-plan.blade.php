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
        var final_value = Number(data['price']) - val0 - val1;
        console.log(final_value);
        $('#fill-plan-line-personal-2').set_money(final_value);
        $('#per-plan-line-personal-2').set_percent(((final_value/data['price'])*100.0));
    }
</script>
@endpush