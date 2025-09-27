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
4.envファイルの1部を以下のように編集

DB_HOST=mysql
DB_DATABASE=laravel_DB
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

5.php artisan key:generate
6.php artisan migrate --seed
7.php artisan storage:link

※サンプル商品画像を storage 側へ配置
# コンテナ内で
1.docker compose exec php bash -lc '
2. mkdir -p storage/app/public/products &&
  cp -n public/images/* storage/app/public/products/ 2>/dev/null || true
'

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