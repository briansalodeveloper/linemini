
@push('assetCss')
    @livewireStyles
@endpush

@push('assetJs')
    @livewireScripts
    <script>
        $(document).on('click', '[lw-wireclick]', function(e) {
            e.preventDefault();

            var event = $(this).attr('lw-wireclick');
            var param = $(this).attr('lw-wireclick-param');

            if (typeof event != 'undefined') {
                if (typeof param != 'undefined') {
                    window.livewire.emit(event, param);
                } else {
                    window.livewire.emit(event);
                }

                if ($(this).parents('.pagination').length != 0) {
                    var targetTable = $(this).data('table');

                    if (typeof targetTable != 'undefined') {
                        if ($(targetTable).length != 0) {
                            // TBODY remove records
                            $(targetTable).find('tbody').html('');

                            // A tag change current active pagelink to user clicked pagelink
                            if (this.tagName.toLowerCase() == 'a') {
                                var elActive = $(this).parents('ul.pagination').find('li.active');
                                elActive.html('<a href="javascript:;">' + elActive.text() + '</a>');

                                if ($(this).attr('rel') == undefined) {
                                    var txt = $(this).text();
                                    $(this).parents('li').html('<span>' + txt + '</span>');
                                }
                            }
                            
                            // After TABLE insert loading
                            var html = '';
                            html += '<div class="text-center align-items-center mt-3">';
                            html += '    <div class="spinner-border" role="status">';
                            html += '        <span class="sr-only">the content is loading...</span>';
                            html += '    </div>';
                            html += '</div>';

                            $(html).insertAfter(targetTable);
                        }
                    }
                }
            }
        });
    </script>
@endpush
