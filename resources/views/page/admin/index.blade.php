@extends('layouts.app')

@section('bodyClass', 'pg-admin pg-admin-index')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fas fa-user-tie"></i> {{ __('words.Administrator') }}
    @endsection
    <a href="{{route('admin.create')}}"><button class="btn btn-dark" >{{ __('words.NewPost') }}</button></a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ __('words.Id') }}</th>
                        <th>{{ __('words.Name') }}</th>
                        <th>{{ __('words.LoginId') }}</th>
                        <th>{{ __('words.Email') }}</th>
                        <th>{{ __('words.Role') }}</th>
                        <th>{{ __('words.RegisteredDate') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $datum)
                        <tr>
                            <td>
                                <a class="btn btn-01" href="{{ route('admin.edit', $datum->id) }}" onclick-showloading>{{ __('words.Edit') }}</a>
                            </td>
                            <td class="id">{{ $datum->id }}</td>
                            <td>{{ $datum->name }}</td>
                            <td>{{ $datum->username }}</td>
                            <td>{{ $datum->email }}</td>
                            <td>{{ $datum->roleStr }}</td>
                            <td>{{ $datum->formatDate('createdDate', 'Y/m/d') }}</td>
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
