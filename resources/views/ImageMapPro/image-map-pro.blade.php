@push('script-includes')
<script src="vendor/listing-utils/js/image-map-pro.min.js"></script>
<script src="vendor/listing-utils/js/imageMapPro.js"></script>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        $('#image-map-pro').imageMapPro(@json($imp->get_info()));
    });
</script>
@endpush