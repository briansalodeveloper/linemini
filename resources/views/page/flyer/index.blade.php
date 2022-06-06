@extends('layouts.app')

@section('bodyClass', 'pg-flyer pg-flyer-index')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="far fa-newspaper"></i> {{ __('words.Flyer') }}
    @endsection
    <a href="{{route('flyer.create')}}"><button class="btn btn-dark" >{{ __('words.NewPost') }}</button></a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ __('words.Id') }}</th>
                        <th>{{ __('words.Title') }}</th>
                        <th>{{ __('words.Status') }}</th>
                        <th>{{ __('words.TargetStore') }}</th>
                        <th>{{ __('words.PublicationDateAndTime') }}</th>
                        <th>{{ __('words.PublicationEndDateAndTime') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $datum)
                        <tr>
                            <td>
                                <a class="btn btn-01" href="{{ route('flyer.edit', $datum->id) }}" onclick-showloading>{{ __('words.Edit') }}</a>
                            </td>
                            <td class="id">{{ $datum->flyerPlanId }}</td>
                            <td>{{ $datum->flyerName }}</td>
                            <td>{{ $datum->statusStr }}</td>
                            <td><div class="limit-width-onwords">{{ $datum->displayStoresStr }}</div></td>
                            <td>{{ $datum->formatDate('startDateTime', 'Y/m/d H:i') }}</td>
                            <td>{{ $datum->formatDate('endDateTime', 'Y/m/d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($data->count() == 0)
                <div class="d-flex justify-content-center mt-4">
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
@endsection
