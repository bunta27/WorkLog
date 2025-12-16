@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin-detail.css') }}">
@endsection

@section('content')
<div class="content detail-content">
    <div class="content__header">
        <h2 class="content__title">勤怠詳細</h2>
    </div>
    <form class="form" action="{{ url('/attendance/' . $attendanceRecord['id']) }}" method="post">
        @csrf
        <div class="form__content">
            <div class="form__group form__group--name">
                <p class="form__header">名前</p>
                <div class="form__input-wrapper">
                    <input class="form__input form__input--name readonly" type="text" value="{{ $user->name }}" readonly>
                </div>
            </div>
            <div class="form__group form__group--date">
                <p class="form__header">日付</p>
                <div class="form__input-wrapper">
                    <input class="form__input readonly" type="text" value="{{ $attendanceRecord['year'] }}" readonly>
                    <input class="form__input readonly" type="text" value="{{ $attendanceRecord['date'] }}" readonly>
                </div>
            </div>
            <div class="form__group form__group--work">
                <p class="form__header">出勤・退勤</p>
                <div class="form__input-wrapper">
                    <div class="form__field">
                        <div class="form__input-group">
                            <input class="form__input" type="text" name="new_clock_in" value="{{ $attendanceRecord['clock_in'] }}">
                            <p>～</p>
                            <input class="form__input" type="text" name="new_clock_out" value="{{ $attendanceRecord['clock_out'] }}">
                        </div>

                        @if($errors->has('new_clock_in') || $errors->has('new_clock_out'))
                            <div class="error__message">
                                <div class="error__message--item">
                                    @error('new_clock_in')
                                        <p>{{ $message }}</p>
                                    @enderror
                                    @error('new_clock_out')
                                        <p>{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $existingBreaks = $attendanceRecord['breaks'] ?? [];
                $breakCount = count($existingBreaks);
                $breakRowCount = $breakCount + ($breakCount === 1 ? 1 : 0);
            @endphp

            @for ($i = 0; $i < $breakRowCount; $i++)
                @php
                    $break = $existingBreaks[$i] ?? ['break_in' => '', 'break_out' => ''];
                    $label = $i === 0 ? '休憩' : '休憩' . ($i + 1);
                @endphp

                <div class="form__group form__group--break">
                    <p class="form__header">{{ $label }}</p>
                    <div class="form__input-wrapper">
                        <div class="form__field">
                            <div class="form__input-group">
                                <input class="form__input" type="text" name="new_break_in[{{ $i }}]" value="{{ $break['break_in'] }}">
                                <p>～</p>
                                <input class="form__input" type="text" name="new_break_out[{{ $i }}]" value="{{ $break['break_out'] }}">
                            </div>

                            @if($errors->has("new_break_in.$i") || $errors->has("new_break_out.$i"))
                                <div class="error__message">
                                    <div class="error__message--item">
                                        @error("new_break_in.$i")
                                            <p>{{ $message }}</p>
                                        @enderror
                                        @error("new_break_out.$i")
                                            <p>{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endfor

            <div class="form__group form__group--remark">
                <p class="form__header">備考</p>
                <div class="form__input-wrapper">
                    <textarea class="form__textarea" name="comment">{{ $attendanceRecord['comment'] }}</textarea>

                    @if($errors->has('comment'))
                        <div class="error__message">
                            @error('comment')
                                <p>{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button--submit" type="submit">修正</button>
        </div>
    </form>
</div>
@endsection