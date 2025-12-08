@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/user-detail.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="content__header">
        <h2 class="content__title">勤怠詳細</h2>
    </div>
    <form class="form" action="{{ url('/attendance/' . $data['id']) }}" method="post">
        @csrf
        @if(is_null($data['application']))
            <div class="form__content">
                <div class="form__group">
                    <p class="form__header">名前</p>
                    <input class="form__input form__input--name" type="text" name="name" value="{{ $user->name }}" readonly>
                </div>
                <div class="form__group">
                    <p class="form__header">日付</p>
                    <input class="form__input" type="text" value="{{ $data['year'] }}" readonly>
                    <input class="form__input" type="text" name="new_date" value="{{ $data['date'] }}">
                </div>
                <div class="form__group">
                    <p class="form__header">出勤・退勤</p>
                    <input class="form__input" type="text" name="new_clock_in" value="{{ $data['clock_in'] }}">
                    <p>～</p>
                    <input class="form__input" type="text" name="new_clock_out" value="{{ $data['clock_out'] }}">
                </div>
                <div class="error__message">
                    <div class="error__message--item">
                        @error('new_clock_in')
                            {{ $message }}
                        @enderror
                        @error('new_clock_out')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__group form__break-group">
                    <p class="form__header">休憩</p>
                    <div class="form__input form__input-wrapper">
                        @foreach($data['breaks'] as $break)
                            <div class="form__input-group">
                                <input class="form__input" type="text" name="new_break_in[]" value="{{ $break['break_in'] }}">
                                <p>～</p>
                                <input class="form__input" type="text" name="new_break_out[]" value="{{ $break['break_out'] }}">
                            </div>
                        @endforeach
                        <div class="form__input-group">
                            <input class="form__input" type="text" name="new_break_in[]" value="">
                            <p>～</p>
                            <input class="form__input" type="text" name="new_break_out[]" value="">
                        </div>
                    </div>
                </div>
                <div class="error__message">
                    <div class="error__message--item">
                        @foreach($errors->get('new_break_in.*') as $messages)
                            @foreach((array) $messages as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        @endforeach
                        @foreach($errors->get('new_break_out.*') as $messages)
                            @foreach((array) $messages as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <div class="form__group">
                    <p class="form__header">備考</p>
                    <textarea class="form__textarea" name="comment">{{ $data['comment'] }}</textarea>
                </div>
                <div class="error__message">
                    @error('comment')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form__button">
                <button class="form__button--submit" type="submit">修正</button>
            </div>

        @else
            <div class="form__content">
                <div class="form__group">
                    <p class="form__header">名前</p>
                    <input class="form__input form__input--name readonly" type="text" name="name" value="{{ $user->name }}" readonly>
                </div>
                <div class="form__group">
                    <p class="form__header">日付</p>
                    <input class="form__input readonly" type="text" value="{{ $data['year'] }}" readonly>
                    <input class="form__input readonly" type="text" value="{{ $data['date'] }}" readonly>
                </div>
                <div class="form__group">
                    <p class="form__header">出勤・退勤</p>
                    <input class="form__input readonly" type="text" value="{{ $data['clock_in'] }}" readonly>
                    <p>～</p>
                    <input class="form__input readonly" type="text" value="{{ $data['clock_out'] }}" readonly>
                </div>
                <div class="form__group form__break-group">
                    <p class="form__header">休憩</p>
                    <div class="form__input-wrapper">
                        @foreach($data['breaks'] as $break)
                            <div class="form__input-group">
                                <input class="form__input readonly" type="text" value="{{ $break['break_in'] }}" readonly>
                                <p>～</p>
                                <input class="form__input readonly" type="text" value="{{ $break['break_out'] }}" readonly>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form__group">
                    <p class="form__header">備考</p>
                    <textarea class="form__textarea readonly" type="text" name="comment" readonly>{{ $data['comment'] }}</textarea>
                </div>
                <div class="form__button">
                    <p class="readonly-message">承認待ちのため修正はできません。</p>
                </div>
            </div>
        @endif
    </form>
</div>
@endsection