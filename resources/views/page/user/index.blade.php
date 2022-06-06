@extends('layouts.app')

@section('bodyClass', 'pg-user pg-user-index')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fa-user"></i> {{ __('words.LineAppStatusChange') }}
    @endsection
    <button class="btn btn-dark" onclick-btn-close>{{ __('words.CloseTab') }}</button>
@endsection

@section('content')
<div class="d-flex justify-content-center">
    <form method="GET" action="{{ route('user.index') }}" class="form-inline mb-4">
        <div class="form-group mr-3">
            <label class="pt-2 mr-2">{{ __('words.UnionMemberNumber') }}</label> 
            <input type="search" class="form-control" name="unionMemberCode" value="{{ request()->get('unionMemberCode', '') }}" placeholder="00000000" maxlength="8">
        </div>
        <div class="form-group mr-3">
            <label class="pt-2 mr-2">{{ __('words.CardNumber') }}</label> 
            <input type="search" class="form-control" name="cardNumber" value="{{ request()->get('cardNumber', '') }}" placeholder="0000000000000000" maxlength="18">
        </div>
        <button type="submit" class="btn btn-secondary me-5">{{ __('words.Search') }}</button>
    </form>
</div>
@if(request()->has('cardNumber') || request()->has('unionMemberCode'))
    <div class="card">
        <div class="card-body table-responsive">
            <form method="POST" action="{{ route('user.updateIncident') }}">
                @csrf
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('words.UnionMemberNumber') }}</th>
                            <th>{{ __('words.CardNumber') }}</th>
                            <th>{{ __('words.CardLinkStatus') }}</th>
                            <th>{{ __('words.CardAvailability') }}</th>
                            <th>{{ __('words.CooperationDate') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $datum)
                            <tr>
                                <input type="hidden" name="countResult[]" id="countResult">
                                <td><input type="checkbox" name="checkbox[]" id="checkbox{{ $key }}" value="{{ $datum->unionLineId }}"{{ $data->total() == 1 ? ' checked' : '' }}></td>
                                <td>{{ $datum->unionMemberCode }}</td>
                                <td>{{ $datum->cardNumber }}</td>
                                <td>{{ $datum->delFlgStr }}</td>
                                <td>{{ $datum->stopFlgStr }}</td>
                                <td>{{ $datum->formatDate('updateDate', 'Y/m/d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                    @if(count($data) != 0)
                        <div class="mt-5 d-flex justify-content-center">
                            <div class="col-md-4 col-sm-10">
                                <select name="incident" id="incident" disabled class="form-control">
                                    @foreach(\Globals::mUnionLine()::INCIDENTALS as $id => $datum)
                                        @if(!is_array($datum))
                                            @php $datum = [$datum]; @endphp
                                        @endif
    
                                        @foreach($datum as $word)
                                            <option value="{{ $id }}">{{ __($word) }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-5 d-flex justify-content-center">
                            <button type="submit" id="btnSubmitFrmList" disabled class="btn btn-02">{{ __('words.Update') }}</button>
                        </div>
                    @endif
                </form>
                @if(count($data) == 0)
                    <div class="d-flex justify-content-center mt-4">
                        <strong>{{ __('words.ThereAreNoRecords') }}</strong>
                    </div>
                @endif
            </div>
            @if($data->lastPage() != 1)
                <div class="card-footer clearfix">
                    <div class="pagination pagination-sm m-0 justify-content-center">
                        <p>{{ \Globals::paginateLinks($data) }}</p>
                    </div>
                </div>
            @endif
        </div>
    @endif
@endsection

@if(request()->has('cardNumber'))
    @push('js')
        <script>
            'use strict';

            /*======================================================================
            * VARIABLES
            *======================================================================*/
        
            let _l = {
                el: {
                    tblRecordCheckboxes: 'table tbody tr input[type="checkbox"]',
                    tblRecordCheckboxesChecked: 'table tbody tr input[type="checkbox"]:checked',
                    tblRecordCheckboxesNotCheck: 'table tbody tr input[type="checkbox"]:not(:checked)',
                    btnSubmitFrmList: '#btnSubmitFrmList',
                },
                words: {
                    modalConfirmTitle: '{{ __('words.FinalConfirmation') }}',
                    modalConfirmMessage: '{{ __('words.AreYouSureYouWantToContinue') }}',
                },
            };

            /*======================================================================
            * DOM EVENTS
            *======================================================================*/

            $(function () {
                /** checkbox (individual checked) **/
                $(document).on('change', _l.el.tblRecordCheckboxes, function () {
                    if ($(_l.el.tblRecordCheckboxesChecked).length != 0) {
                        $('#incident').prop('disabled', false);
                        $(_l.el.btnSubmitFrmList).prop('disabled', false);
                    } else {
                        $('#incident').prop('disabled', true);
                        $(_l.el.btnSubmitFrmList).prop('disabled', true);
                    }
                });

                /** BUTTON SUBMIT **/
                $(_l.el.btnSubmitFrmList).on('click', function (e) {
                    e.preventDefault();

                    _g.modal.show(_l.words.modalConfirmTitle, _l.words.modalConfirmMessage, function () {
                        $(_l.el.btnSubmitFrmList).unbind('click').click();
                        _g.loading.show();
                    });
                });
            });

            /*======================================================================
            * DOM INITIAL
            *======================================================================*/

            $(function () {
                $(_l.el.tblRecordCheckboxesChecked).change();
            });
        </script>
    @endpush
@endif