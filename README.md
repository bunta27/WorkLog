# 勤怠管理システム（WorkLog）

Laravel を用いて作成した勤怠管理アプリケーションです。  
ユーザーの出勤・退勤・休憩管理、勤怠修正申請、管理者による承認機能を備えています。

---

## 概要

- 出勤 / 退勤 / 休憩の打刻管理
- 月別の勤怠一覧表示
- 勤怠修正申請（ユーザー）
- 勤怠修正の承認フロー（管理者）
- メール認証対応（Fortify）
- 管理者・一般ユーザーの権限分離

---

## 環境構築

### Docker ビルド

```bash
git clone git@github.com:bunta27/WorkLog.git
docker-compose up -d --build
```

※ MySQL は OS や環境によって起動しない場合があるため、必要に応じて
docker-compose.yml を各自の環境に合わせて調整してください。

### Laravel セットアップ
1. docker-compose exec php bash
2. composer install
3. cp .env.example .env
4. .envファイルの変更

```bash
DB_HOSTをmysqlに変更
DB_DATABASEをlaravel_dbに変更
DB_USERNAMEをlaravel_userに変更
DB_PASSをlaravel_passに変更
MAIL_FROM_ADDRESSに送信元アドレスを設定
```
5. php artisan key:generate
6. php artisan migrate
7. php artisan db:seed
8. php artisan test

## usersテーブル（ユーザー）

| カラム名 | 型 | 制約 | 説明 |
|---------|----|------|------|
| id | bigint | PK | ユーザーID |
| name | string | NOT NULL | ユーザー名 |
| email | string | UNIQUE, NOT NULL | メールアドレス |
| password | string | NOT NULL | パスワード |
| attendance_status | string |  | 勤怠状態（勤務外 / 出勤中 / 休憩中 / 退勤済） |
| is_admin | boolean |  | 管理者フラグ |
| email_verified_at | timestamp |  | メール認証日時 |
| created_at | timestamp |  | 作成日時 |
| updated_at | timestamp |  | 更新日時 |

---

## attendance_recordsテーブル（勤怠）

| カラム名 | 型 | 制約 | 説明 |
|---------|----|------|------|
| id | bigint | PK | 勤怠レコードID |
| user_id | bigint | FK, NOT NULL | ユーザーID |
| date | date | NOT NULL | 勤務日 |
| clock_in | datetime |  | 出勤時刻 |
| clock_out | datetime |  | 退勤時刻 |
| total_time | string |  | 実働時間 |
| total_break_time | string |  | 合計休憩時間 |
| comment | text |  | コメント |
| created_at | timestamp |  | 作成日時 |
| updated_at | timestamp |  | 更新日時 |

---

## breaksテーブル（休憩）
| カラム名 | 型 | 制約 | 説明 |
|---------|----|------|------|
| id | bigint | PK | 休憩ID |
| attendance_record_id | bigint | FK, NOT NULL | 勤怠レコードID |
| break_in | time |  | 休憩開始時刻 |
| break_out | time |  | 休憩終了時刻 |
| created_at | timestamp |  | 作成日時 |
| updated_at | timestamp |  | 更新日時 |

---

## applicationsテーブル（勤怠修正申請）
| カラム名 | 型 | 制約 | 説明 |
|---------|----|------|------|
| id | bigint | PK | 休憩ID |
| attendance_record_id | bigint | FK, NOT NULL | 勤怠レコードID |
| break_in | time |  | 休憩開始時刻 |
| break_out | time |  | 休憩終了時刻 |
| created_at | timestamp |  | 作成日時 |
| updated_at | timestamp |  | 更新日時 |

---

## application_breaksテーブル（修正申請・休憩）

| カラム名 | 型 | 制約 | 説明 |
|---------|----|------|------|
| id | bigint | PK | 申請ID |
| user_id | bigint | FK, NOT NULL | 申請者ユーザーID |
| attendance_record_id | bigint | FK, NOT NULL | 対象勤怠ID |
| approval_status | string | NOT NULL | 承認状態（承認待ち / 承認 / 却下） |
| application_date | date | NOT NULL | 申請日 |
| new_date | date |  | 修正後日付 |
| new_clock_in | time |  | 修正後出勤時刻 |
| new_clock_out | time |  | 修正後退勤時刻 |
| comment | text | NOT NULL | 申請理由 |
| created_at | timestamp |  | 作成日時 |
| updated_at | timestamp |  | 更新日時 |

---

## ログイン情報
### 一般ユーザー
ID：user1@example.com／user2@example.com／user3@example.com  
PASS：password
### 管理者
ID：admin@example.com  
PASS：password

## 使用技術（実行環境）

- PHP 8.0
- Laravel 10.x
- MySQL 8.0
- Docker / Docker Compose

### URL

- 開発環境: http://localhost/
- phpMyAdmin: http://localhost:8080/
- Mailhog: http://localhost:8025/


### ER図

![ER図](docs/worklog.png)

## 備考
### 勤怠ステータス
- 勤務外
- 出勤中
- 休憩中
- 退勤済