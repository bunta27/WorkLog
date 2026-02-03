@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff-attendance-list.css') }}">
@endsection

@section('content')
<div class="content staff-attendance-content">
    <div class="content__header">
        <h2 class="content__title">{{ $user->name }}さんの勤怠</h2>
    </div>
    <div class="content__menu">
        <a class="previous-month" href="?date={{ $previousMonth }}">前月</a>
        <p class="current-month">{{ $date->format('Y/m') }}</p>
        @if ($date->lt(\Carbon\Carbon::create(2026, 12, 31)))
            <a class="next-month" href="?date={{ $nextMonth }}">翌月</a>
        @else
            <div class="next-month-placeholder"></div>
        @endif
    </div>
    <table class="table">
        <colgroup>
            <col class="col-date">
            <col class="col-clock-in">
            <col class="col-clock-out">
            <col class="col-break">
            <col class="col-total">
            <col class="col-detail">
        </colgroup>
        <tr class="table__row">
            <th class="table__header">
                <p class="table__header--item">日付</p>
            </th>
            <th class="table__header">
                <p class="table__header--item">出勤</p>
            </th>
            <th class="table__header">
                <p class="table__header--item">退勤</p>
            </th>
            <th class="table__header">
                <p class="table__header--item">休憩</p>
            </th>
            <th class="table__header">
                <p class="table__header--item">合計</p>
            </th>
            <th class="table__header">
                <p class="table__header--item">詳細</p>
            </th>
        </tr>
        @foreach($formattedAttendanceRecords as $attendanceRecords)
        <tr class="table__row">
            <td class="table__description">
                <p class="table__description--item">{{ $attendanceRecords['date'] }}</p>
            </td>
            <td class="table__description">
                <p class="table__description--item">{{ $attendanceRecords['clock_in'] }}</p>
            </td>
            <td class="table__description">
                <p class="table__description--item">{{ $attendanceRecords['clock_out'] }}</p>
            </td>
            <td class="table__description">
                <p class="table__description--item">{{ $attendanceRecords['total_break_time'] }}</p>
            </td>
            <td class="table__description">
                <p class="table__description--item">{{ $attendanceRecords['total_time'] }}</p>
            </td>
            <td class="table__description">
                @if (!empty($attendanceRecords['id']))
                    <a class="table__item--detail--link" href="{{ url('/admin/attendance/' . $attendanceRecords['id']) }}">詳細</a>
                @else
                    <p>詳細</p>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    <div class="csv__button">
        <form action="{{ route('admin.attendance.export') }}" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="year_month" value="{{ $date->format('Y-m') }}">
            <button class="form__button csv__button--submit" type="submit">
                CSV出力
            </button>
        </form>
    </div>
</div>
@endsection