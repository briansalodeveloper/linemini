<div wire:init="lwPgLoadData">
    <div class="card union-member-codes">
        <div class="card-body table-responsive">
            <table id="tblUnionMemberCodes" class="table text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center" colspan="4">{{ __('words.UnionMemberCodeTable') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($pgListPaginate['list'] as $unionMemberCodes)
                            <tr>
                            @foreach ($unionMemberCodes as $code)
                                <td >{{ $code }}</td>
                            @endforeach
                            @if ($loop->last && count($unionMemberCodes) != 4)
                                @for ($i = count($unionMemberCodes) % 4; $i < 4; $i++)
                                    <td></td>
                                @endfor
                            @endif
                            </tr>
                        @endforeach
                    </tr>
                </tbody>
            </table>
            @if($this->lwHasLoadDataFlag() ? !$this->pgLoadData : false)
                <div class="text-center align-items-center mt-3">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">the content is loading...</span>
                    </div>
                </div>
            @else
                @if($pgListPaginate['list']->count() == 0)
                    <div class="d-flex justify-content-center mt-3">
                        <strong>{{ __('words.ThereAreNoRecords') }}</strong>
                    </div>
                @endif
            @endif
        </div>
        @if($pgListPaginate['list']->lastPage() != 1)
            <div class="card-footer clearfix">
                <div class="pagination pagination-sm m-0 justify-content-center">
                    {!! $pgListPaginate['list']->links('vendor.pagination.livewire', [
                        'tableId' => '#tblUnionMemberCodes'
                    ]) !!}
                </div>
            </div>
        @endif
    </div>
</div>
