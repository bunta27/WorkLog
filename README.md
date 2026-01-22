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

## 必要要件（最低限）

- Docker / Docker Compose
- Git（任意：clone に使用）

---

## 環境構築

### Docker ビルド

```bash
git clone git@github.com:bunta27/WorkLog.git
cd WorkLog
```

### Laravel セットアップ

#### ホスト側(コンテナ外)
```bash
cp .env.example .env
```

- .envの設定（例）

※ DB設定は必須です。Mailhog を使わない場合は `MAIL_*` は環境に合わせて変更/削除してください。

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS=example@worklog.com
MAIL_FROM_NAME="WorkLog"
```

```bash
docker compose up -d --build
```

#### コンテナ側（Dockerの中)
```bash
docker compose exec php bash -lc "composer install"
docker compose exec php bash -lc "php artisan key:generate"
docker compose exec php bash -lc "php artisan migrate --seed"
docker compose exec php bash -lc "php artisan test"
```

---

## 動作確認

※ セットアップ完了後、http://localhost/ にアクセスしてログイン画面が表示されればOKです。  
※ メール認証の動作確認は Mailhog http://localhost:8025/ で受信できればOKです。

### URL
- 開発環境: http://localhost/
- 管理者ログイン: http://localhost/admin/login
- phpMyAdmin: http://localhost:8080/
- Mailhog: http://localhost:8025/ （開発用メール受信確認）

### ログイン情報
| 種別 | Email | Password |
|---|---|---|
| 一般ユーザー | user1@example.com / user2@example.com / user3@example.com | password |
| 管理者 | admin@example.com | password |

---

## トラブルシューティング

### `storage/logs/laravel.log` や `storage/framework/sessions` で Permission denied が出る場合

```bash
docker compose exec php bash -lc "
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache &&
chown -R www-data:www-data storage bootstrap/cache &&
chmod -R ug+rwX storage bootstrap/cache
"
```

### MySQL が起動しない場合（例: ポート3306競合 / volume不整合）
まずログを確認してください：

```bash
docker compose logs mysql
```
- ローカルで 3306 を使っているサービスがないか

---

## 使用技術（実行環境）

- PHP 8.1.34
- Laravel 8.83.29
- MySQL 8.0.26
- Docker 29.1.3/ Docker Compose v2.40.3
- 開発環境のメール送信確認に Mailhog を使用

## ER図

![ER図](docs/worklog.png)

---

## テーブル定義

### usersテーブル（ユーザー）

| カラム名 | 型 | primary key | unique key | not null | foreign key |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | 〇 |  | 〇 |  |
| name | string |  |  | 〇 |  |
| email | string |  | 〇 | 〇 |  |
| password | string |  |  | 〇 |  |
| attendance_status | string |  |  | 〇 |  |
| admin_status | boolean |  |  | 〇 |  |
| email_verified_at | timestamp |  |  |  |  |
| remember_token | string |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

<br>

### attendance_recordsテーブル（勤怠）

| カラム名 | 型 | primary key | unique key | not null | foreign key |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | 〇 |  | 〇 |  |
| user_id | bigint |  |  | 〇 | users(id) |
| date | date |  |  | 〇 |  |
| clock_in | datetime |  |  |  |  |
| clock_out | datetime |  |  |  |  |
| total_time | string |  |  |  |  |
| total_break_time | string |  |  |  |  |
| comment | text |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

<br>

### breaksテーブル（休憩）

| カラム名 | 型 | primary key | unique key | not null | foreign key |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | 〇 |  | 〇 |  |
| attendance_record_id | bigint |  |  | 〇 | attendance_records(id) |
| break_in | time |  |  |  |  |
| break_out | time |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

<br>

### applicationsテーブル（勤怠修正申請）

| カラム名 | 型 | primary key | unique key | not null | foreign key |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | 〇 |  | 〇 |  |
| user_id | bigint |  |  | 〇 | users(id) |
| attendance_record_id | bigint |  |  | 〇 | attendance_records(id) |
| approval_status | string |  |  | 〇 |  |
| application_date | date |  |  | 〇 |  |
| new_date | date |  |  | 〇 |  |
| new_clock_in | time |  |  | 〇 |  |
| new_clock_out | time |  |  |  |  |
| comment | text |  |  | 〇 |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

<br>

### application_breaksテーブル（修正申請・休憩）

| カラム名 | 型 | primary key | unique key | not null | foreign key |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | 〇 |  | 〇 |  |
| application_id | bigint |  |  | 〇 | applications(id) |
| break_in | time |  |  | 〇 |  |
| break_out | time |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

---

## 備考
### 勤怠ステータス
- 勤務外
- 出勤中
- 休憩中
- 退勤済