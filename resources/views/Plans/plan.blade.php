<p class="plan-title" id="plan-title-{{ $plan->id }}">{{ $plan->title }}</p>
<div class="table-responsive">
    <table class="table">
        <tbody>
            
            @if(isset($form))
            @foreach ($plan->top_lines as $line)
                "{{ $line->render($plan->id, strval($loop->index).'_top', $form) }}"
            @endforeach
            @foreach ($plan->lines as $line)
                {!! $line->render($plan->id, $loop->index) !!}
            @endforeach
            @else
            @foreach ($plan->top_lines as $line)
                {!! $line->render($plan->id, $loop->index) !!}
            @endforeach
            @foreach ($plan->lines as $line)
                {!! $line->render($plan->id, $loop->index) !!}
            @endforeach
            @endif
            @if(isset($form))
            @foreach ($plan->bottom_lines as $line)
                {!! $line->render($plan->id, strval($loop->index).'_bottom', $form) !!}
            @endforeach
            @else
            @foreach ($plan->bottom_lines as $line)
                {!! $line->render($plan->id, $loop->index) !!}
            @endforeach
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
        console.log(final_price);
        @endif
    @else
        var final_price = parseFloat(data['{{ $plan->discount }}']);
    @endif
    $('#fill-total-price-{{ $plan->id }}').set_money(final_price);
    @stack($stack)
@endpush