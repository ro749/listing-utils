<x-dynamic-component :component="$element->line->component" :element="$element->line" />

<x-dynamic-component :component="$element->months_line->component" :element="$element->months_line" />

<x-plan-line :element="$element->mensuality_line" />


@push($element->plan_id)
    @if(is_numeric($element->num_of_months))
        var months = {{$element->num_of_months}};
    @elseif(!empty($element->num_of_months))
        var months = data['{{$element->num_of_months}}'];
    @endif

    
    @if(!empty($element->num_of_months))
        $('#fill_{{ $element->months_line->id }}').text(months);
    @else
    
    @endif
    
    
@endpush
@push('scripts')
<script>
    $('#fill_{{ $element->months_line->id }}').on('input', function () {
        fill_data();
    });
    $(document).on('personalized_plan_changed', function(){
        var value = $('#fill_{{ $element->line->id }}').get_number();
        @if(empty($element->num_of_months))
            months = $('#fill_{{ $element->months_line->id }}').get_number();
        @endif
        if(months <= 0){
            months = 1;
        }
        $('#fill_{{ $element->mensuality_line->id }}').set_money(value/months);
    });
    </script>
@endpush
