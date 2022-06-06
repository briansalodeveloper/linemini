@push('assetCss')
    <link href="{{ _vers('assets/admin-lte/plugins/ekko-lightbox/ekko-lightbox.css') }}" rel="stylesheet">
@endpush

@push('assetJs')
    <script src="{{ _vers('assets/admin-lte/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(e) {
            e.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
@endpush
