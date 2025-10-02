@if(!empty($unit))
    @foreach($unit->characteristics as $characteristic)
    <div class="characteristic">
        @if(!empty($icons_path) && !empty($icons_ext))
        <img class="characteristic-icon" src="{{ $icons_path }}{{ $characteristic->icon }}{{ $icons_ext }}">
        @endif
        <div class="characteristic-text">{{ $characteristic->text }}</div>
    </div>
    @endforeach
@endif


@if(empty($unit))
@push('fill')
$('#characteristics').empty();
for(var i = 0; i < data['characteristics'].length; i++){
    var char = data['characteristics'][i];
    $('#characteristics').append(`
        <div class="characteristic">
            @if(!empty($icons_path) && !empty($icons_ext))
            <img class="characteristic-icon" src="{{ $icons_path }}`+char['icon']+`{{ $icons_ext }}"">
            @endif
            <div class="characteristic-text">`+char['text']+`</div>
        </div>
    `);
}
@endpush
@endif