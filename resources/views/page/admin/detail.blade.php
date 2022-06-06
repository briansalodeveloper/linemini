@extends('layouts.app')

@section('bodyClass', 'pg-flyer pg-flyer-detail')

@include('assets.bootstrapSwitch')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fa-user-tie"></i>
        {{ $data->isEmpty ? __('words.AdministratorNewPost') : __('words.AdministratorEditPost') }}
    @endsection
    @include('common.menu.detailMenu', [
        'page' => 'admin'
    ])
@endsection

@php
    $route = $data->isEmpty ? route('admin.store') : route('admin.update', $data->id);
    $routeList = route('admin.index');
    $routeDelete = '';
    $confirmationModal = __('words.FinalConfirmation');
    $deleteConfirmationModal = __('words.DoYouWishToProceedDeletion');

    if ($data->isNotEmpty) {
        $routeDelete = route('admin.destroy', $data->id);
    }
@endphp

@section('content')
    <form method="POST" enctype="multipart/form-data" id="form" action="{{ $route }}">
        @csrf
        {{-- id --}}
            @if($data->isNotEmpty)
                <input type="text" hidden name="id" value="{{ $data->id }}">
            @endif
            <div class="form-group mt-3">
                <div class="row">
                    @if($data->isNotEmpty)
                        <div class="col-12">
                            <label>{{ __('words.Id') }}: {{ $data->getAttr('id') }}</label>
                        </div>
                    @endif
                </div>
            </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-6">
                {{-- name --}}
                    <div class="form-group">
                        <label for="name">{{ __('words.Name') }} <req></req></label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" name="name" id="name" placeholder="" value="{{ old('name', $data->getAttr('name')) }}" data-original-value="{{ $data->getAttr('name') }}">
                                @include('common.validationError', ['key' => 'name'])
                            </div>
                        </div>
                    </div>
                {{-- username --}}
                    <div class="form-group">
                        <label for="username">{{ __('words.LoginId') }} <req></req></label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control @if($errors->has('username')) is-invalid @endif" name="username" id="username" placeholder="" value="{{ old('username', $data->getAttr('username')) }}" data-original-value="{{ $data->getAttr('username') }}">
                                @include('common.validationError', ['key' => 'username'])
                            </div>
                        </div>
                    </div>
                {{-- email --}}
                    <div class="form-group">
                        <label for="email">{{ __('words.Email') }}</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control @if($errors->has('email')) is-invalid @endif" name="email" id="email" placeholder="" value="{{ old('email', $data->getAttr('email')) }}" data-original-value="{{ $data->getAttr('email') }}">
                                @include('common.validationError', ['key' => 'email'])
                            </div>
                        </div>
                    </div>
                {{-- updatePassword --}}
                    @if($data->isNotEmpty)
                        <div class="form-group">
                            <label for="updatePassword">{{ __('words.UpdatePassword') }}</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="checkbox" id="updatePassword" name="updatePassword" data-bootstrap-switch data-on-text="{{ __('words.Enable') }}" data-off-text="{{ __('words.Disabled') }}" value="1"{{ old('updatePassword', false) ? ' checked' : '' }} data-original-value>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div id="updatePasswordContainer">
                        <div class="form-group">
                            <label for="password">{{ __('words.Password') }} <req></req></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-password @if($errors->has('password')) is-invalid @endif">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="" value="{!! $errors->has('passwordConfirmation') && !$errors->has('password') ? old('pw') : '' !!}" data-original-value>
                                        <div class="input-group-append" onclick-password-eye data-target="#password">
                                            <span class="input-group-text"><i class="fas fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                    @include('common.validationError', ['key' => 'password'])
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('words.ConfirmationPassword') }} <req></req></label>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-password @if($errors->has('passwordConfirmation')) is-invalid @endif">
                                        <input type="password" class="form-control" name="passwordConfirmation" id="passwordConfirmation" data-original-value>
                                        <div class="input-group-append" onclick-password-eye data-target="#passwordConfirmation">
                                            <span class="input-group-text"><i class="fas fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                    @include('common.validationError', ['key' => 'passwordConfirmation'])
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- role --}}
                    <div class="form-group">
                        <label>{{ __('words.Role') }} <req></req></label>
                        <div class="col-12{{ $errors->has('role') ? ' is-invalid' : '' }}">
                            @foreach($listRole as $key => $val)
                                <div class="iradio-primary">
                                    <input type="radio" id="publishType{{ $key }}" value="{{ $key }}"
                                        name="role" {!! old('role', $data->getAttr('role', '-1')) == $key ? 'checked': '' !!}
                                        data-original-value="{{ $data->getAttr('role') }}">
                                    <label for="publishType{{ $key }}">{{ __($val) }}</label>
                                </div>
                            @endforeach
                        </div>
                        @include('common.validationError', ['key' => 'role'])
                    </div>
            </div>                
        </div>
        <div class="row justify-content-center mb-3">
            <button id="submit" type="submit" class="btn btn-warning col-md-3 col-sm-3 w-100 text-white">{{ __('words.Post') }}</button>
        </div>
        <div class="row justify-content-end mt-2 mb-3">
            <a href="{{ $routeList }}" class="btn btn-02">{{ __('words.BackToList') }}</a>
        </div>
    </form>
@endsection

@if($data->isNotEmpty)
    @push('js')
        <script>
            'use strict';

            /*======================================================================
            * VARIABLES
            *======================================================================*/

            let _l = {
                undoEdit: function () {
                    _g.form.undoEdit('form');
                }
            };

            /*======================================================================
            * DOM EVENTS
            *======================================================================*/

            $(function () {
                /** BUTTON DELETE **/
                $('#deleteBtn').click(function(e) {
                    e.preventDefault();
                    let callback = function () {
                        @if(!empty($routeDelete))
                            $('#form').attr('action', "{{ $routeDelete }}");
                            $("#submit").unbind('click').click();
                        @endif
                    }
                    _g.modal.show('{{ $confirmationModal }}', '{{ $deleteConfirmationModal}}', callback);
                });

                /** BUTTON CLEAR & UNDO **/
                $('#clearBtn, #undoEdit').click(function() {
                    _l.undoEdit();
                });

                /** CHECKBOX updatePassword **/
                $('#updatePassword').on('switchChange.bootstrapSwitch', function (event, state) {
                    if ($(this).prop('checked')) {
                        $('#updatePasswordContainer').show();
                    } else {
                        $('#updatePasswordContainer').hide();
                    }
                }); 

            });
    
            /*======================================================================
            * DOM INITIAL
            *======================================================================*/

            $(function () {
                $('#updatePassword').trigger('switchChange.bootstrapSwitch');
            });
        </script>
    @endpush
@endif