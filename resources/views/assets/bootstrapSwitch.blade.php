@push('assetCss')
    <link href="{{ _vers('assets/admin-lte/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.css') }}" rel="stylesheet">
@endpush

@push('assetJs')
    <script src="{{ _vers('assets/admin-lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
    </script>
@endpush
