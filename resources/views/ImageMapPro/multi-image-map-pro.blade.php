@push('script-includes')
<script src="vendor/listing-utils/js/image-map-pro.min.js"></script>
<script src="vendor/listing-utils/js/multiImageMapPro.js"></script>
@endpush
@push('scripts')
<script>
    $(document).ready(function () {
        $('#image-map-pro').multiImageMapPro(@json($imp));
    });
</script>
@endpush