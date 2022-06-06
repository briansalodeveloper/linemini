@php
    $tableClass = '';
    $whitespace = ' ';
    $add = [
        'class' => [
            'card' => isset($classes['card']) ? $whitespace . $classes['card'] : ''
        ]
    ];
@endphp

<div class="card{{ $add['class']['card'] }}">
    <div class="card-body table-responsive">
        <table class="table text-nowrap">
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            @if ($showData)
                <tbody>
                    @foreach($data as $datum)
                        <tr>
                            @foreach($datum as $tdItem)
                                <td  @if ($loop->iteration == array_search($headerID, $headers) + 1) class="id" @endif>{!! $tdItem !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        @if($data->count() == 0 || !$showData)
            <div class="d-flex justify-content-center mt-3">
                <strong>{{ __('words.ThereAreNoRecords') }}</strong>
            </div>
        @endif
    </div>
    @if($data->lastPage() != 1)
        <div class="card-footer clearfix">
            <div class="pagination pagination-sm m-0 justify-content-center">
                {{ $data->links() }}
            </div>
        </div>
    @endif
</div>
