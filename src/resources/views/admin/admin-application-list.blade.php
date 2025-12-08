@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin-application-list.css') }}">
@endsection

@section('content')
<div class="application-list__content">
    <div class="content__header">
        <h2 class="content__title">申請一覧</h2>
    </div>
    <div class="application-list__tab">
        <label class="application-list__tab--label" for="tab1">
            <input class="application-list__tab--input" type="radio" id="tab1" name="tab-item" checked>
            承認待ち
        </label>
        <label class="application-list__tab--label" for="tab2">
            <input class="application-list__tab--input" type="radio" id="tab2" name="tab-item">
            承認済み
        </label>
        <div  class="application-list__tab--content">
            <table class="table">
                <tr class="table__row">
                    <th class="table__header">
                        <p class="table__header--item">状態</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">名前</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">対象日時</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">申請理由</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">申請日時</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">詳細</p>
                    </th>
                </tr>
                @foreach($formattedApplications as $application)
                @if($application->approval_status === '承認待ち')
                <tr class="table__row">
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->approval_status }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->user->name }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->attendanceRecord->date->format('Y/m/d') }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->comment }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->application_date }}</p>
                    </td>
                    <td class="table__description">
                        <a class="table__item--detail--link" href="{{ url('/stamp_correction_request/approve' . $application['id']) }}">詳細</a>
                    </td>
                </tr>
                @endif
                @endforeach
            </table>
        </div>
        <div  class="application-list__tab--content">
            <table class="table">
                <tr class="table__row">
                    <th class="table__header">
                        <p class="table__header--item">状態</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">名前</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">対象日時</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">申請理由</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">申請日時</p>
                    </th>
                    <th class="table__header">
                        <p class="table__header--item">詳細</p>
                    </th>
                </tr>
                @foreach($formattedApplications as $application)
                @if($application->approval_status === '承認済み')
                <tr class="table__row">
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->approval_status }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->user->name }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->attendanceRecord->date->format('Y/m/d') }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->comment }}</p>
                    </td>
                    <td class="table__description">
                        <p class="table__description--item">{{ $application->application_date }}</p>
                    </td>
                    <td class="table__description">
                        <a class="table__item--detail--link" href="{{ url('/stamp_correction_request/approve' . $application['id']) }}">詳細</a>
                    </td>
                </tr>
                @endif
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection