@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function () {
            $('#image-map-pro').multiImageMapPro(@json($imp));
        });
    });
</script>
@endpush