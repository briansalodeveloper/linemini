@extends('layouts.app')

@section('bodyClass', 'pg-message pg-message-index')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fa-envelope"></i> {{ __('words.Message') }} {{ __('words.TransmissionHistory') }}
    @endsection
    <a href="{{route('message.create')}}"><button class="btn btn-dark" >{{ __('words.CreateNew') }}</button></a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ __('words.Id') }}</th>
                        <th>{{ __('words.ManagementName') }}</th>
                        <th>{{ __('words.DateAndTimeTransmission') }}</th>
                        <th>{{ __('words.Status') }}</th>
                        <th>{{ __('words.TransmissionObject') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $datum)
                        <tr>
                            <td>
                                <a href="{{ route('message.edit', $datum->id) }}" onclick-showloading><button class="btn btn-01">{{
                                    $datum->isStatusSend ? __('words.Detail') : __('words.Edit')
                                }}</button></a>
                            </td>
                            <td class="id">{{ $datum->id }}</td>
                            <td>{{ $datum->messageName }}</td>
                            <td>{{ $datum->formatDate('sendDateTime', 'Y/m/d H:i') }}</td>
                            <td>{{ $datum->statusStr }}</td>
                            <td>{{ $datum->sendTargetFlgStr }}</td>
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
                    <p>{{ $data->links()}}</p>
                </div>
            </div>
        @endif
    </div>
@endsection
