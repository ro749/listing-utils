<div class="plan-div" id="plan-div-{{ $plan->id }}">
    <p class="plan-title" id="plan-title-{{ $plan->id }}">{{ $plan->title }}"></p>
    <div class="table-responsive">
        <table class="table">
            <tbody>
                @if($plan->show_base_price)
                    @include('Plans.plan-line',[
                        'description' => $plan->price_tag,
                        'percentage' => 0,
                        'class' => 'base-price',
                        'id' => 'base-price-'.$plan->id,
                    ])
                @endif
                @if($plan->discount != 0)
                    @include('Plans.plan-line',[
                        'description' => $plan->discount_tag,
                        'percentage' => $plan->discount,
                        'class' => 'discount',
                        'id' => 'discount-'.$plan->id,
                    ])
                @endif
                
                @if($plan->total_on_top)
                    @include('Plans.plan-totals',['plan'=>$plan])
                @endif

                @foreach ($plan->lines as $line)
                    {!! $line->render($plan->id, $loop->index) !!}
                @endforeach

                @if(!$plan->total_on_top)
                    @include('Plans.plan-totals',['plan'=>$plan])
                @endif

            </tbody>
        </table>
    </div>
</div>