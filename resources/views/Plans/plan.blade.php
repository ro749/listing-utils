@props(['personal_plan'=>null])
<p class="plan-title" id="plan-title-{{ $plan->id }}">{{ $plan->title }}</p>
<div class="table-responsive">
    <table class="table">
        <tbody>
            
            @foreach ($plan->top_lines as $line)
                <x-dynamic-component :component="$line->component" :element="$line"/>
            @endforeach
            @foreach ($plan->lines as $line)
                <x-dynamic-component :component="$line->component" :element="$line"/>
            @endforeach
            @foreach ($plan->bottom_lines as $line)
                <x-dynamic-component :component="$line->component" :element="$line"/>
            @endforeach

        </tbody>
    </table>
</div>
@push('fill')
    @if($plan->show_base_price)
        $('#fill-base-price-{{ $plan->id }}').set_money(data['price']);         
    @endif
    @if($plan->ppm)
        $('#fill-ppm-price-{{ $plan->id }}').set_money(data['price']/data['total']);
    @endif
    @if(is_numeric($plan->discount))
        @if( $plan->discount != 0)
        var discount = data['price'] * {{ $plan->discount / 100.0 }};
        var final_price = data['price'] - discount;
        @else
        var final_price = parseFloat(data['price']); 
        var discount_element = $('#fill_discount{{ $plan->id == 'personalized' ? '' : '_'.$plan->id }}');
        if(discount_element){
            if(discount_element.is('input')){
                if(discount_element.data('flag')){
                    $('#per_discount{{ $plan->id == 'personalized' ? '' : '_'.$plan->id }}').set_value(discount/data['price']*100.0);
                }
                else{
                    var discount_percent = $('#per_discount{{ $plan->id == 'personalized' ? '' : '_'.$plan->id }}').get_number();

                    discount_element.set_value((discount_percent/100.0)*data['price']);
                }
            }
            var discount = discount_element.get_number();
            final_price -= discount;
            $('#per_discount{{ $plan->id == 'personalized' ? '' : '_'.$plan->id }}').set_value(discount/data['price']*100.0);
        }
        @endif
    @else
        var final_price = parseFloat(data['{{ $plan->discount }}']);
    @endif
    $('#fill_total-price-{{ $plan->id }}').set_money(final_price);
    @stack($id)
@endpush