@push('assetCss')
    <link href="{{ _vers('assets/prismjs/themes/prism.min.css') }}" rel="stylesheet">
    <link href="{{ _vers('assets/prismjs/plugins/line-highlight/prism-line-highlight.min.css') }}" rel="stylesheet">
    <link href="{{ _vers('assets/trumbowyg/ui/trumbowyg.min.css') }}" rel="stylesheet">
    <link href="{{ _vers('assets/trumbowyg/plugins/colors/ui/trumbowyg.colors.min.css') }}" rel="stylesheet">
    <link href="{{ _vers('assets/trumbowyg/plugins/emoji/ui/trumbowyg.emoji.min.css') }}" rel="stylesheet">
@endpush

@push('assetJs')
    <script src="{{ _vers('assets/prismjs/prism.js') }}"></script>
    <script src="{{ _vers('assets/prismjs/plugins/line-highlight/prism-line-highlight.min.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/trumbowyg.min.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/langs/ja.min.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/plugins/upload/trumbowyg.upload.min.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/plugins/colors/trumbowyg.colors.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/plugins/fontsize/trumbowyg.fontsize.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/plugins/fontfamily/trumbowyg.fontfamily.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/plugins/highlight/trumbowyg.highlight.js') }}"></script>
    <script src="{{ _vers('assets/trumbowyg/plugins/emoji/trumbowyg.emoji.js') }}"></script>
    <script>
        $.trumbowyg.svgPath = '{{ url('svg/trumbowyg/icons.svg') }}';
    </script>
@endpush
