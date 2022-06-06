@extends('layouts.app')

@section('bodyClass', 'pg-stamp pg-stamp-index')

@section('content')
    <div class="d-flex justify-content-between m-4">
        <h3><i class="fas fa-stamp"></i>&nbsp{{__('words.Stamp') }}</h3>
        <a href="{{route('stamp.create')}}" class="btn btn-secondary">{{__('words.NewPost') }}</a>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card card-seemless">
                            <div class="card-body table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th width="10"></th>
                                            <th>{{ __('words.Id') }}</th>
                                            <th>{{ __('words.Title') }}</th>
                                            <th>{{ __('words.Status') }}</th>
                                            <th>{{ __('words.kinds') }}</th>
                                            <th>{{ __('words.PublicationDateAndTime') }}</th>
                                            <th>{{ __('words.PublicationEndDateAndTime') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $datum)
                                        <tr>
                                            <td> 
                                                <a class="btn btn-01" href="{{ route('stamp.edit', $datum->id) }}" onclick-showloading>{{ __('words.Edit') }}</a>
                                            </td>
                                            <td class="id">{{ $datum->stampPlanId }}</td>
                                            <td>{{ $datum->stampName}}</td>
                                            <td>{{ $datum->StatusStr }}</td>
                                            <td>{{ $datum->StampTypeStr }}</td>
                                            <td>{{ $datum->formatDate('startDate', 'Y/m/d')}} {{$datum->startTime}}</td>
                                            <td>{{ $datum->formatDate('startTime', 'Y/m/d')}} {{$datum->endTime}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer clearfix">
                                <div class="pagination pagination-sm m-0 justify-content-center">
                                    {{ $data->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
