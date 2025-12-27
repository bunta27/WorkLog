<?php

namespace Tests\Support;

final class TestRoutes
{
    // 認証
    public const REGISTER = '/register';
    public const LOGIN = '/login';
    public const ADMIN_LOGIN = '/admin/login';

    // 申請一覧（一般・管理者で同一URL運用ならそのまま）
    public const STAMP_CORRECTION_LIST = '/stamp_correction_request/list';

    // 承認画面
    public static function approveUrl(int $applicationId): string
    {
        return "/stamp_correction_request/approve/{$applicationId}";
    }

    // 勤怠打刻（あなたの実装に合わせて。違ったらここだけ変える）
    public const ATTENDANCE_INDEX = '/attendance';

    // 勤怠詳細（あなたが使ってたリンク）
    public static function attendanceDetailUrl(int $attendanceRecordId): string
    {
        return "/attendance/detail/{$attendanceRecordId}";
    }

    // 勤怠修正申請（Userの勤怠詳細のform actionに合わせる想定）
    public static function attendanceUpdateUrl(int $attendanceRecordId): string
    {
        return "/attendance/{$attendanceRecordId}";
    }
}
