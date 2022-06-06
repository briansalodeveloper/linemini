@push('assetCss')
    <link href="{{ _vers('assets/admin-lte/plugins/select2/css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ _vers('assets/admin-lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet"/>
@endpush

@push('assetJs')
    <script src="{{ _vers('assets/admin-lte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ _vers('assets/admin-lte/plugins/select2/js/i18n/ja.js') }}"></script>
    <script>
        $(function () {
            $('select[auto-init-select2]').each(function () {
                $(this).select2();
            });
            
            $('select[auto-init-select2-bs4]').each(function () {
                $(this).select2({
                    theme: 'bootstrap4'
                });
            });
        });
    </script>
@endpush
