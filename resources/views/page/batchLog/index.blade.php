@extends('layouts.app')

@section('bodyClass', 'pg-batchlog pg-batchlog-index')
@include('assets.datetimepicker')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fa-file"></i> {{ __('words.BatchLog') }}
    @endsection
@endsection

@push('modals')
    @include('modals.batchLog.preview')
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" action="{{ route('batchLog.index') }}">
                <div class="d-flex justify-content-center">
                    <div class="col-md-4">
                        <input type="search" id="date" class="form-control datetimepicker-input" placeholder="{{ __('words.DateSearch') }}" name="date" value="{{ request()->get('date', '') }}" autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-secondary me-5">{{ __('words.Search') }}</button>
                </div>
            </form>
        </div>
    </div>
    @if(request()->has('date'))
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th>{{ __('words.ActualDateAndTime') }}</th>
                            <th>{{ __('words.LogFile') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $key => $datum)
                            <tr>
                                <input type="hidden" name="countResult[]" id="countResult">
                                <td>{{ $datum['createdAt'] }}</td>
                                <td>
                                    <a href="{{ $datum['path'] }}" data-name="{{ $datum['customFileName'] }}" download role="btnPreview">{{ $datum['customFileName'] }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

@push('js')
    <script>
        'use strict';

        /*======================================================================
        * DOM INITIAL
        *======================================================================*/

        $(function () {
            $('#batchLogPreview .overlay').hide();
        });

        /*======================================================================
        * DOM EVENTS
        *======================================================================*/

        $(function () {
            $('#date').datetimepicker({
                format: 'L',
                allowInputToggle: true,
            });

            $(document).on('click', '[role="btnPreview"]', function (e) {
                e.preventDefault();
                var path = $(this).attr('href');
                var name = $(this).data('name');

                $('[role-name=location]').html(name);
                $('[role-name=contents]').html('');
                $('#batchLogPreview .overlay').show();
                $('#batchLogPreview').modal('show');

                $.post(
                    "{{ route('batchLog.show') }}",
                    {
                        _token: _g.token,
                        path: path
                    },
                    function (data) {
                        $('[role-name=contents]').html(data.contents);
                    }
                ).always(function () {
                    $('#batchLogPreview .overlay').hide();
                });
            });
        });
    </script>
@endpush