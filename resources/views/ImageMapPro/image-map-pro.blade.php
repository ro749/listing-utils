@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function () {
            $('#image-map-pro').imageMapPro(@json($imp->get_info()));
        });
    });
</script>
@endpush