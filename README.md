# Rese
飲食店予約アプリ  
ユーザーが簡単にネット上で飲食店の予約をすることができるアプリです。このアプリは、レストランの検索、予約、レビュー、店舗のお気に入り登録等の機能を備えています。管理者から店舗代表者に設定された者は、店舗情報の作成、予約状況の確認、コースの設定、メール送信等の機能を提供します。

<img width="1687" alt="スクリーンショット 2024-05-24 17 49 03" src="https://github.com/Guttyan/reservation-system/assets/141023258/f5e718ad-8b70-415f-8cb5-9bb33047a085">

## 作成した目的
ユーザーが簡単に店舗を予約することができるアプリを作成したかったため。

## 機能一覧
### ユーザー向け機能

1. **会員登録機能**
    - メールによる本人確認を行う。
2. **ログイン機能**
3. **ログアウト機能**
4. **飲食店一覧表示機能**
5. **飲食店検索機能**
    - エリア、ジャンル、店舗名で検索可能。それぞれの条件でAND検索可能。
6. **店舗お気に入り機能**
    - 店舗一覧の右下のハートマークをクリックすることで、お気に入り登録、解除が可能。
7. **店舗予約機能**
    - 店舗詳細画面から店舗の予約が可能。予約の際は現在日時以降のみ予約可能。予約時間は12:00〜23:45で設定可能。
8. **事前決済機能**
    - 店舗代表者がコースを店舗に設定した場合、コースを選択した場合に限り、事前決済可能。コースを選択しても事前決済せずに通常予約も可能。事前決済画面に以降した場合、メールアドレス「test@example.com」、カード番号「4242 4242 4242 4242」、その他については任意で設定し、事前決済可能。
9. **マイページ機能**
    - マイページを表示すると、現在の自身の予約状況、お気に入り店舗が表示される。予約状況については予約開始時間から３時間経過したものについては表示されない。お気に入り店舗はマイページからでもお気に入り解除可能。
10. **予約情報編集機能**
    - マイページの予約状況で、予約の右上の×ボタンを押すことでキャンセル可能。予約状況をクリックすることで、予約編集画面へ遷移し、予約の編集、削除が可能。
11. **QRコード表示機能**
    - 予約が完了した時点で、QRコードが発行される。QRコードはマイページの予約状況右下の「QRコード表示」ボタンをクリックすることにより表示。QRコードには予約IDが記されている。
12. **リマインダー機能**
    - 予約当日の朝８時に予約リマインダーメールが予約者に送信される。リマインダーメールにはQRコード及び、マイページへの遷移ボタンが表示されている。
13. **評価機能**
    - メニューボタンの「Write A Review」ボタンをクリックすると、過去の自身の予約一覧が表示される。その内の店舗を選択することにより、店舗の５段階評価、コメントが可能。レビュー内容については、店舗一覧ページで５段階の平均が表示され、店舗詳細ページにてレビュー内容が確認可能。

### 管理者向け機能

管理者は「admin@example.com」、パスワード「11111111」で設定してあるため、それでログイン可能。

1. **店舗代表者設定機能**
    - メニュー画面の「Create Representative」をクリックすると、ユーザー一覧ページに遷移。そこからユーザーを選択することにより、店舗代表者を設定可能。
2. **メール送信機能**
    - メニュー画面の「Send Email」をクリックすると、メール作成画面に遷移。メールの件名、宛先、内容を設定し、ユーザーにメールの送信可能。

### 店舗代表者向け機能

店舗代表者は管理者が設定することにより、店舗代表者の機能が使用可能。

1. **店舗作成機能**
    - メニュー画面の「Create Shop」をクリックすると、店舗作成画面に遷移。店舗写真については複数枚選択可能。その場合、店舗詳細画面ではスライドショーで写真が表示される。
2. **自身の店舗一覧表示**
    - メニュー画面の「My Shops」で自身が作成した店舗一覧を表示。
3. **店舗情報更新機能**
    - 店舗一覧から店舗情報更新が可能。
3. **コース作成、編集機能**
    - 店舗一覧の「コース管理」から、コースの新規作成、編集、削除が可能。
4. **予約状況確認機能**
    - 店舗一覧の「予約状況確認」から、自身の選択した店舗の日毎の予約状況が確認可能。
6. **メール送信機能**
    - 店舗一覧の「メール送信」から、メール作成画面へ遷移。ユーザーへメール送信可能。


## 使用技術
Laravel Framework 8.83.27  
PHP 8.3.0  
MySQL 8.0.26  
MailHog  

## テーブル設計
<img width="1058" alt="スクリーンショット 2024-05-24 18 44 02" src="https://github.com/Guttyan/reservation-system/assets/141023258/491c9ee0-fe3b-45c6-818a-8f8943816faf">
<img width="500" alt="スクリーンショット 2024-05-24 18 44 19" src="https://github.com/Guttyan/reservation-system/assets/141023258/0ad2925f-e2a7-4f1e-a823-230949dafbc1">

## ER図
<img width="544" alt="スクリーンショット 2024-05-24 18 45 50" src="https://github.com/Guttyan/reservation-system/assets/141023258/ea43a4dd-1f06-41ff-b8d1-afd5ea0ecb61">


# 環境構築
**Dockerビルド**  
git clone https://github.com/Guttyan/reservation-system.git  
cd reservation-system  
docker-compose up -d --build  

**Laravel環境構築**  
docker-compose exec php bash  
composer install --ignore-platform-req=ext-gd  
cd src  
cp .env.example .env　環境変数を変更  
Mailhog環境変数  
MAIL_FROM_ADDRESS=your-email@example.com  
php artisan key:generate  
php artisan migrate  
php artisan db:seed  
composer require swiftmailer/swiftmailer  

スケジュールの設定  
apt-get update  
apt-get install nano  
apt-get install cron  
crontab -e  
0 8 * * * PATH=/usr/local/bin/php:/usr/bin:/bin /usr/local/bin/php /var/www/artisan reservation:reminder >> /var/www/storage/logs/laravel.log 2>&1　を記述  
service cron start  
dpkg-reconfigure tzdata　対話方式に従ってAsia/Tokyoに設定  

composer require laravel/cashier  
composer require stripe/stripe-php  
.envファイルに下記を記述  
STRIPE_KEY=your-stripe-key  
STRIPE_SECRET=your-stripe-secret  

storage/app/public/shop_imagesディレクトリを作成  
storage/app/public/qr_codesディレクトリを作成  
storage/app/public/review_imagesディレクトリを作成  
php artisan storage:link  

QRコード  
apt-get update  
apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev  
docker-php-ext-configure gd --with-freetype --with-jpeg  
docker-php-ext-install gd  
composer require simplesoftwareio/simple-qrcode  
composer require intervention/image  