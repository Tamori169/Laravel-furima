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

`.env.example` をもとに `.env` を作成し、以下の項目を設定してください。

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

本アプリではメール認証機能の確認に MailHog を使用しています。  
Docker起動後、MailHog は `http://localhost:8025` で確認が可能です。

`.env` への設定内容は下記の通りです。

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Stripe設定

本アプリでは Stripe を利用した決済機能を実装しています。

### 1. セットアップ手順

決済機能を確認するには、Stripe のテスト用 API キーの設定が必要なため、下記手順に従い、セットアップを実施してください。

1. Stripeにログイン（アカウントがない場合は作成要）
2. テストモードを有効化
3. APIキー一覧から以下を確認

- 公開可能キー
- シークレットキー

4. Stripe CLI を使用してWebhook署名を取得

- 下記コマンドを実行(実行後に表示される whsec\_... を控える)

```bash
docker-compose exec php bash
stripe login
stripe listen --forward-to http://nginx/stripe/webhook
```

5. `.env` に設定

```env
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxx
```

### 2. 決済手順

決済の際には、事前に下記コマンドを実行してから決済処理を行なってください。

```
docker-compose exec php bash
stripe login
stripe listen --forward-to http://nginx/stripe/webhook
```

## 機能テスト実行手順

`config/database.php` にはテスト用DB接続設定を記述済みです。  
以下の手順でテスト用DBと環境ファイルを作成後、テストを実行してください。

### 1. MySQLにログイン

```
cd Laravel-furima
docker-compose exec mysql bash
```

### 2. rootにアクセス

```
mysql -u root -p
パスワードは `root` を入力してください。
```

### 3. テスト用DBを作成

```
CREATE DATABASE laravel_furima_test;
exit
```

### 4. .env.testingを作成

```
docker-compose exec php bash
cp .env .env.testing
```

### 5. 環境変数設定

```.env.testing
APP_ENV=testing
DB_DATABASE=laravel_furima_test
DB_USERNAME=root
DB_PASSWORD=root
```

### 6. 各種データをクリア

```
php artisan config:clear
php artisan cache:clear
```

### 7. データベースマイグレーション

```
php artisan migrate --env=testing
```

### 8. テスト実行

```
php artisan test
```

## テストユーザー

### 1. ユーザー情報一覧

シーディングにより、下記３名の動作確認用テストユーザーが作成されます。  
各ユーザーの認証状況およびプロフィール設定状況、出品商品情報は下記の通りです。  
また、各ユーザーに応じた想定用途も記載しているので、それぞれ動作確認に活用してください。

テストユーザー1：メール認証済み・プロフィール設定済み

- ユーザ名：田中太郎
- メールアドレス：tanaka@example.com
- パスワード：tanakatanaka
- 出品した商品：腕時計・HDD・玉ねぎ3束
- 郵便番号：100-0005
- 住所：東京都千代田区丸の内一丁目
- 建物名：丸の内オアゾ 15F
- 想定用途：ログイン後の各機能動作確認

テストユーザー2：メール認証未済・プロフィール設定未済

- ユーザ名：佐藤次郎
- メールアドレス：sato@example.com
- パスワード：satosato
- 出品した商品：革靴・ノートPC・マイク
- 郵便番号・住所・建物名：登録なし
- 想定用途：初回ログイン時のメール認証からプロフィール設定までの導線確認（メール認証誘導画面では、認証メール再送信が必要です）

テストユーザ3：メール認証済・プロフィール設定未済

- ユーザ名：鈴木三郎
- メールアドレス：suzuki@example.com
- パスワード：suzukisuzuki
- 出品した商品：ショルダーバッグ・タンブラー・コーヒーミル・メイクセット
- 郵便番号・住所・建物名：登録なし
- 想定用途：購入画面での発送先入力によるバリデーションのほか、途中離脱による想定外動作の確認

### 2. （参考）プロフィール設定用サンプルデータ

| ユーザーNO      | 郵便番号 | 住所                                | 建物名                     | プロフィール画像                                   |
| --------------- | -------- | ----------------------------------- | -------------------------- | -------------------------------------------------- |
| テストユーザー2 | 530-0011 | 大阪府大阪市北区大深町              | グランフロント大阪 北館 7F | `public/images/profiles/sato_profile_image.jpeg`   |
| テストユーザー3 | 460-0008 | 愛知県名古屋市中区栄三丁目 15番21号 | スカイプラザ栄 402号室     | `public/images/profiles/suzuki_profile_image.jpeg` |

なお、プロフィール画像を設定する場合は、Gitクローン後のプロジェクト内にある上記パスの画像ファイルをアップロードしてください。
