@extends('layouts.app')

@section('bodyClass', 'pg-coupon pg-coupon-index')

@section('contentHeader')
    @section('contentHeaderTitle')
        <i class="fas fa-ticket-simple"></i> {{ __('words.Coupon') }}
    @endsection
    <a href="{{ route('coupon.create') }}"><button class="btn btn-dark" >{{ __('words.NewPost') }}</button></a>
@endsection

@php
    $data->getCollection()->transform( function ($value) {
        return  [
            '<a class="btn btn-01" href=' . route("coupon.edit", $value->id)  .' onclick-showloading> ' .  __("words.Edit") . '</a>',
            $value->id,
            $value->cuponName,
            $value->statusStr,
            $value->couponTypeStr,
            $value->formatDate('startDateTime', 'Y/m/d H:i'),
            $value->formatDate('endDateTime', 'Y/m/d H:i'),
        ];
    });
@endphp

@section('content')
@include('common.couponPlan.table', [
    'headers' => [
        '',
        __('words.Id'),
        __('words.Title'),
        __('words.Status'),
        __('words.Kinds'),
        __('words.StartDateTime'),
        __('words.EndDateTime')
    ],
    'showData' => true,
    'data' => $data,
    'headerID' => __('words.Id')
])

@endsection
