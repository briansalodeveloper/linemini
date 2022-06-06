<div wire:init="lwPgLoadData">
    <div class="card coupon-category-data">
        <div class="card-body table-responsive">
            <table id="tblCouponTargetClass" class="table text-nowrap">
                <thead>
                    <tr>
                        <th>{{ __('words.Id') }}</th>
                        <th>{{ __('words.Department') }}</th>
                        <th>{{ __('words.MiddleClassification') }}</th>
                        <th>{{ __('words.Subclass') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pgListPaginate['list'] as $datum)
                        @php
                            $department = $datum['departmentCode'] == '0' ? __('words.All') : '(' . $datum['departmentCode'] . ') ' . $datum['departmentCodeName'];
                            $middleClassification =  $datum['middleClassificationCode'] == '0' ? __('words.All') : '(' . $datum['middleClassificationCode'] . ') ' . $datum['middleClassificationCodeName'];
                            $subclass = $datum['subclassCode'] == '0' ? __('words.All') : '(' . $datum['subclassCode'] . ') ' . $datum['subclassCodeName'];
                        @endphp
                        <tr>
                            <td class="id">{{ $datum->id }}</td>
                            <td>{{ $datum->departmentClassificationCodeName }}</td>
                            <td>{{ $datum->middleClassificationCodeName }}</td>
                            <td>{{ $datum->subClassificationCodeName }}</td>
                        </tr>
                    @endforeach
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
                        'tableId' => '#tblCouponTargetClass'
                    ]) !!}
                </div>
            </div>
        @endif
    </div>
</div>
