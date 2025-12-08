@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin-application-detail.css') }}">
@endsection

@section('content')
<div class="application-detail__content">
    <div class="content__header">
        <h2 class="content__title">勤怠詳細</h2>
    </div>
    <form class="form" action="{{ url('/stamp_correction_request/approve' . $application['id']) }}" method="post">
        @csrf
        <div class="form__content">
            <div class="form__group">
                <p class="form__header">名前</p>
                <input class="form__input form__input--name" type="text" name="name" value="{{ $user->name }}" readonly>
            </div>
            <div class="form__group">
                <p class="form__header">日付</p>
                <input class="form__input" type="text" value="{{ $application->new_date->format('Y年') }}" readonly>
                <input class="form__input" type="text" value="{{ $application->new_date->format('m月d日') }}" readonly>
            </div>
            <div class="form__group">
                <p class="form__header">出勤・退勤</p>
                <input class="form__input" type="text" value="{{ $application->new_clock_in }}" readonly>
                <p>～</p>
                <input class="form__input" type="text" value="{{ $application->new_clock_out }}" readonly>
            </div>
            <div class="form__group form__break-group">
                <p class="form__header">休憩</p>
                <div class="form__input form__input-wrapper">
                    @foreach($application->proposalBreaks as $break)
                        <div class="form__input-group">
                            <input class="form__input" type="text" name="new_break_in[]" value="{{ \Carbon\Carbon::parse($break['break_in'])->format('H:i') }}" readonly>
                            <p>～</p>
                            <input class="form__input" type="text" name="new_break_out[]" value="{{ \Carbon\Carbon::parse($break['break_out'])->format('H:i') }}" readonly>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="form__group">
                <p class="form__header">備考</p>
                <textarea class="form__textarea" type="text" name="comment" value="{{ $application->comment }}" readonly>
            </div>
        </div>
        <div class="form__button">
            @if($application->approval_status === '承認待ち')
                <button class="form__button--submit" type="submit" value="承認">承認</button>
            @elseif($application->approval_status === '承認済み')
                <p class="form__item">承認済み</p>
            @endif
        </div>
    </form>
</div>
@endsection