# furima-app

フリマアプリ（Laravel + Docker）

## Dockerビルド
git clone https://github.com/maruenjin/furima-app.git
cd furima-app

docker compose up -d --build

## 環境構築

1.docker compose exec php bash
2.composer install
3.cp .env.example .env
4.envファイルを以下のように編集

APP_NAME="Furima-app"
APP_ENV=local
APP_URL=http://localhost
APP_DEBUG=true

DB_HOST=mysql
DB_DATABASE=laravel_DB
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

# 画像配信は public ディスク
FILESYSTEM_DISK=public

# MailHog（メール認証/パスワードリセット）
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

5.php artisan key:generate
6.php artisan migrate --seed
7.php artisan storage:link

※サンプル商品画像を storage 側へ配置
# コンテナ内で
docker compose exec php bash -lc '
mkdir -p storage/app/public/products &&
cp -n public/images/* storage/app/public/products/ 2>/dev/null || true
'

## 環境差異（MySQL のバージョン/アーキテクチャ）

既定では mysql:8.0.26 を使用していますが、ARM(M1/M2 など) では起動できない/不安定な場合があります。
その場合は以下のいずれかを選択してください。
A. 8.0 の最新パッチ or LTS に切り替える
docker-compose.yml の MySQL イメージを変更：

services:
  mysql:
    # 安定版（自動で最新パッチを取得）
    image: mysql:8.0
    # もしくは LTS
    # image: mysql:8.4
その後に再作成：

docker compose down -v
docker compose pull
docker compose up -d --build

B. 8.0.26 を使う場合（Apple Silicon 向け）
docker-compose.override.yml を作成して エミュレーションを指定：

services:
  mysql:
    platform: linux/amd64

 docker compose down -v
docker compose up -d --build   

## ER図
[ER図](docs/er/furima_er.png)
[編集用 .drawio](docs/er/furima_er.drawio)


## 開発環境

アプリトップ：http://localhost/
新規登録：http://localhost/register
ログイン：http://localhost/login
phpMyAdmin：http://localhost:8080/

## 使用技術（実行環境）

PHP / Laravel 10.x
MySQL 8.x
Nginx
Docker / Docker Compose
PHPUnit

## テスト（コンテナ内で）
php artisan test