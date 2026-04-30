# アプリケーション名

Coachtechフリマ

## アプリケーション概要

フリマアプリです。ユーザは会員登録後、アプリ内で商品の出品や商品購入ができます。

## 使用技術(実行環境)

- PHP 8.1.34
- Laravel 8.83.8
- MySQL 8.0.26
- Docker
- Docker Compose
- phpMyAdmin
- MailHog
- Stripe
- Git
- GitHub

## 環境構築

### 1. リポジトリをクローン

```
git clone https://github.com/Tamori169/Laravel-furima.git  
cd Laravel-furima
```

### 2. Dockerコンテナを作成・起動

```
docker-compose up -d --build
```

### 3. PHPコンテナに入る

```
docker-compose exec php bash
```

### 4. Composerパッケージをインストール

```
composer install
```

### 5. .envファイルを作成

```
cp .env.example .env
```

### 6. アプリケーションキーを作成

```
php artisan key:generate
```

### 7. 環境変数の設定

詳細は「環境変数」の項目を参照

### 8. データベースマイグレーション

```
php artisan migrate
```

### 9. シーディング実行

```
php artisan db:seed
```

### 10. ストレージのシンボリックリンクを作成（画像アップロード用）

```
php artisan storage:link
```

### "The stream or file could not be opened"エラーが発生した場合

srcディレクトリにあるstorageディレクトリに権限を設定
```
chmod -R 777 storage
```

## 環境変数

`.env.example` をもとに `.env` を作成し、以下の項目を設定。

- `APP_KEY`
  - `php artisan key:generate` で生成（「環境構築」項目を参照）
- `DB_HOST=mysql`
- `DB_DATABASE=laravel_db`
- `DB_USERNAME=laravel_user`
- `DB_PASSWORD=laravel_pass`
- `MAIL_FROM_ADDRESS=test@example.com`
- `STRIPE_KEY`
  - Stripeのテスト用公開キーを設定（詳細は「stripe設定」を参照）
- `STRIPE_SECRET`
  - Stripeのテスト用秘密キーを設定（詳細は「stripe設定」を参照）
- `STRIPE_WEBHOOK_SECRET`
  - `stripe listen --forward-to http://nginx/stripe/webhook` を実行し、表示された `whsec_...` を設定（詳細は「stripe設定」を参照）

## ER図

![ER図](ER.drawio.png)  

## URL一覧

### 1. 認証不要でアクセス可能なページ一覧
- 商品一覧画面(トップ画面)：`http://localhost/`
- 商品詳細画面：`http://localhost/item/{item_id}`
- 会員登録画面：`http://localhost/register`
- ログイン画面：`http://localhost/login`

### 2. 認証後にアクセス可能なページ一覧
- メール認証誘導画面： `http://localhost/email/verify`
- プロフィール設定画面： `http://localhost/setup-profile`
- 商品購入画面： `http://localhost/purchase/{item_id}`
- 送付先住所変更画面： `http://localhost/purchase/address/{item_id}`
- プロフィール画面： `http://localhost/mypage`
- プロフィール編集画面： `http://localhost/mypage/profile`
- 商品出品画面： `http://localhost/sell`

### 3. DB管理画面
- phpMyAdmin：`http://localhost:8080`

## MailHog設定

本アプリではメール認証機能の確認に MailHog を使用。  
Docker起動後、MailHog は `http://localhost:8025` で確認が可能。

`.env` への設定内容は下記の通り。

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Stripe設定

本アプリでは Stripe を利用した決済機能を実装。  
決済機能を確認するには、Stripe のテスト用 API キーの設定が必要なため、下記手順に従い、セットアップを行うこと。

### 1. Stripeのテスト用APIキーを取得

1. Stripeにログイン（アカウントがない場合は作成要）
2. テストモードを有効化
3. APIキー一覧から以下を確認
- 公開可能キー
- シークレットキー
4. Stripe CLI を使用してWebhook署名を取得
- 下記コマンドを実行(実行後に表示される whsec_... を控える)
```bash
docker-compose exec php bash
stripe login
stripe listen --forward-to http://nginx/stripe/webhook
```


### 2. `.env` に設定

```env
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxx
```