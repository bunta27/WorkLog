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

### テスト実行

```bash
php artisan test
```

## ログイン情報
### 一般ユーザー
 id：user1@example.com／user2@example.com／user3@example.com
 pass：password
### 管理者
 id：admin@example.com
 pass：password
 
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

![ER図]()

## 備考
### 勤怠ステータス
- 勤務外
- 出勤中
- 休憩中
- 退勤済