<p class="plan-title" id="plan-title-{{ $plan->id }}">{{ $plan->title }}</p>
<div class="table-responsive">
    <table class="table">
        <tbody>
            @if($plan->show_base_price)
                @include('listing-utils::Plans.plan-line',[
                    'description' => $plan->price_tag,
                    'percentage' => 0,
                    'class' => 'base-price',
                    'id' => 'base-price-'.$plan->id,
                ])
            @endif
            @if(is_numeric($plan->discount) && $plan->discount != 0)
                @include('listing-utils::Plans.plan-line',[
                    'description' => $plan->discount_tag,
                    'percentage' => $plan->discount,
                    'class' => 'discount',
                    'id' => 'discount-'.$plan->id,
                ])
            @endif
            
            @if($plan->total_on_top)
                @include('listing-utils::Plans.plan-totals',['plan'=>$plan])
            @endif
            @if(isset($form))
            @foreach ($plan->lines as $line)
                {!! $line->render($plan->id, $loop->index, $form) !!}
            @endforeach
            @else
            @foreach ($plan->lines as $line)
                {!! $line->render($plan->id, $loop->index) !!}
            @endforeach
            @endif
            @if(!$plan->total_on_top)
                @include('listing-utils::Plans.plan-totals',['plan'=>$plan])
            @endif
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
        $('#fill-discount-{{ $plan->id }}').set_money(discount);
        @else
        var final_price = parseFloat(data['price']); 
        @endif
    @else
        var final_price = parseFloat(data['{{ $plan->discount }}']);
    @endif
    $('#fill-total-price-{{ $plan->id }}').set_money(final_price);
    @stack($stack)
@endpush