@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin-application-list.css') }}">
@endsection

@section('content')
<div class="content admin-application-content">
    <div class="content__header">
        <h2 class="content__title">申請一覧</h2>
    </div>

    <div class="application-list">
        <input type="radio" name="application-tab" class="application-list__tab-input tab-input--pending" id="application-tab-pending" checked>
        <label class="application-list__tab-label" for="application-tab-pending">
            承認待ち
        </label>

        <input type="radio" name="application-tab" class="application-list__tab-input tab-input--approved" id="application-tab-approved">
        <label class="application-list__tab-label" for="application-tab-approved">
            承認済み
        </label>

        <div class="application-list__tab-border"></div>

        <table class="table application-table">
            <colgroup>
                <col class="col-status">
                <col class="col-name">
                <col class="col-target">
                <col class="col-reason">
                <col class="col-applied">
                <col class="col-detail">
            </colgroup>

            <thead>
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
            </thead>

            <tbody class="application-tbody application-tbody--pending">
                @foreach($formattedApplications as $application)
                    @if($application['approval_status'] === '承認待ち')
                        <tr class="table__row">
                            <td class="table__description col-status">
                                <p class="table__description--item">{{ $application['approval_status'] }}</p>
                            </td>
                            <td class="table__description col-name">
                                <p class="table__description--item">{{ $application['user_name'] }}</p>
                            </td>
                            <td class="table__description">
                                <p class="table__description--item">{{ $application['date'] }}</p>
                            </td>
                            <td class="table__description col-reason">
                                <p class="table__description--item">{{ $application['comment'] ?? 'なし' }}</p>
                            </td>
                            <td class="table__description">
                                <p class="table__description--item">{{ $application['application_date'] }}</p>
                            </td>
                            <td class="table__description col-detail">
                                <a class="table__item--detail--link" href="{{ url('/stamp_correction_request/approve/' . $application['id']) }}">
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

            <tbody class="application-tbody application-tbody--approved">
                @foreach($formattedApplications as $application)
                    @if($application['approval_status'] === '承認済み')
                        <tr class="table__row">
                            <td class="table__description col-status">
                                <p class="table__description--item">{{ $application['approval_status'] }}</p>
                            </td>
                            <td class="table__description col-name">
                                <p class="table__description--item">{{ $application['user_name'] }}</p>
                            </td>
                            <td class="table__description">
                                <p class="table__description--item">{{ $application['date'] }}</p>
                            </td>
                            <td class="table__description col-reason">
                                <p class="table__description--item">{{ $application['comment'] ?? 'なし' }}</p>
                            </td>
                            <td class="table__description">
                                <p class="table__description--item">{{ $application['application_date'] }}</p>
                            </td>
                            <td class="table__description col-detail">
                                <a class="table__item--detail--link" href="{{ url('/stamp_correction_request/approve/' . $application['id']) }}">
                                    詳細
                                </a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
