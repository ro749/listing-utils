@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#{{ $imp->get_id() }}').singleImageMapPro(@json($imp->get_info()));
    });
</script>
@endpush