@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/user-application-list.css') }}">
@endsection

@section('content')
<div class="content application-content">
    <div class="content__header">
        <h2 class="content__title">申請一覧</h2>
    </div>

    <div class="application-list">

        <input type="radio" name="application-tab" id="application-tab-pending" class="application-list__tab-input tab-input--pending" checked>
        <label class="application-list__tab-label" for="application-tab-pending">
            承認待ち
        </label>

        <input type="radio" name="application-tab" id="application-tab-approved" class="application-list__tab-input tab-input--approved">
        <label class="application-list__tab-label" for="application-tab-approved">
            承認済み
        </label>

        <div class="application-list__tab-border"></div>

        <div class="application-list__tab-content application-list__tab-content--pending">
            @include('user.partials.application-table', [
                'applications' => $formattedApplications,
                'user' => $user,
                'status' => '承認待ち',
            ])
        </div>

        <div class="application-list__tab-content application-list__tab-content--approved">
            @include('user.partials.application-table', [
                'applications' => $formattedApplications,
                'user' => $user,
                'status' => '承認済み',
            ])
        </div>

    </div>
</div>
@endsection
