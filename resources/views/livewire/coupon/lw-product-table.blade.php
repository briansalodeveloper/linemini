<div wire:init="lwPgLoadData">
    <div class="card coupon-products-data">
        <div class="card-body table-responsive">
            <table id="tblCouponProduct" class="table text-nowrap">
                <thead>
                    <tr>
                        <th>{{ __('words.Id') }}</th>
                        <th>{{ __('words.ProductCode') }}</th>
                        <th>{{ __('words.ProductName') }}</th>
                        <th>{{ __('words.ProductText') }}</th>
                        <th>{{ __('words.ProductImg') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pgListPaginate['list'] as $datum)
                        <tr>
                            <td class="id">{{ $datum->getAttr('id') }}</td>
                            <td>{{ $datum->getAttr('productJancode') }}</td>
                            <td>{{ $datum->getAttr('productName') }}</td>
                            <td>{{ empty($datum->getAttr('productText')) ? 'N/A' : $datum->getAttr('productText') }}</td>
                            <td>
                                @if($datum->getAttr('IsThumbnailExist'))
                                    <a href="{{ $datum->getAttr('productImg') }}" data-toggle="lightbox" title="{{ __('words.Preview') }}"><i class="fa fa-image"></i>{{ \Globals::hUpload()::getBaseName($datum->getAttr('productImg')) }}</a>
                                @else
                                    <i class="fa fa-circle-xmark text-red" title="{{ __('messages.custom.imageNotExist') }}"></i>{{ \Globals::hUpload()::getBaseName($datum->getAttr('productImg')) }}
                                @endif
                            </td>
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
                        'tableId' => '#tblCouponProduct'
                    ]) !!}
                </div>
            </div>
        @endif
    </div>
</div>
