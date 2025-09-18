# demo-ai-php-laravel-hrm

# HRM Laravel (PostgreSQL)

## Setup

1. Install dependencies:
   ```
   composer install
   ```

2. Copy `.env.example` to `.env` and set your PostgreSQL credentials.

3. Generate app key:
   ```
   php artisan key:generate
   ```

4. Run migrations and seeders:
   ```
   php artisan migrate --seed
   ```

5. Publish JWT config and generate secret:
   ```
   php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
   php artisan jwt:secret
   ```

6. Start the server:
   ```
   php artisan serve
   ```

## Requirements

- PHP 8.1+
- PostgreSQL 12+
- `pgsql` and `pdo_pgsql` PHP extensions

## Testing

```
php artisan test
```

**Lý do lỗi "Could not open input file: artisan":**

- Bạn đang chạy lệnh ở sai thư mục. File `artisan` phải nằm ở thư mục gốc của project Laravel.
- Hãy chắc chắn bạn đã `cd` vào đúng thư mục chứa file `artisan` (thường là thư mục gốc của source code Laravel, ví dụ: `/Volumes/WORK/08.DemoAI/PHPLaravel/HRM/demo-ai-php-laravel-hrm`).
- Nếu chưa clone hoặc giải nén source code đầy đủ, hãy kiểm tra lại.

**Cách khắc phục:**
```sh
cd /Volumes/WORK/08.DemoAI/PHPLaravel/HRM/demo-ai-php-laravel-hrm
php artisan key:generate
```

**Nếu bạn đã ở đúng thư mục gốc nhưng không thấy file `artisan`, có thể do:**

- Bạn chưa chạy lệnh `composer create-project` hoặc clone repository Laravel đầy đủ.
- Source code bị thiếu file hoặc bị xóa nhầm file `artisan`.
- Nếu bạn chỉ copy các file con (app, config, ...) mà không copy file gốc của Laravel, sẽ thiếu file `artisan` và các file hệ thống khác.

**Cách khắc phục:**
1. Đảm bảo bạn đã tạo project Laravel đúng cách, ví dụ:
   ```sh
   composer create-project laravel/laravel demo-ai-php-laravel-hrm
   ```
   hoặc clone repository đầy đủ từ nguồn tin cậy.
2. Nếu đã có source code, kiểm tra lại trong thư mục gốc phải có file `artisan` và các thư mục như `app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `vendor`, v.v.
3. Nếu thiếu file, hãy tạo lại project hoặc copy lại toàn bộ source code đầy đủ.

## Khắc phục lỗi thiếu file `artisan` và đảm bảo project Laravel đầy đủ

Nếu bạn không thấy file `artisan` trong thư mục gốc, hãy thực hiện các bước sau để tạo lại project Laravel đầy đủ mà không mất các file cấu hình, migration, controller, ... đã có:

1. **Backup lại các thư mục/app, config, database, routes, ... nếu đã có chỉnh sửa.**

2. **Tạo lại project Laravel đầy đủ vào một thư mục tạm:**
   ```sh
   composer create-project laravel/laravel temp-laravel
   ```

3. **Copy file và thư mục hệ thống còn thiếu từ `temp-laravel` sang thư mục project của bạn:**
   - Các file/thư mục bắt buộc: `artisan`, `bootstrap/`, `public/`, `vendor/`, `composer.json`, `composer.lock`, `package.json`, `webpack.mix.js`, ...
   - Không ghi đè các thư mục mà bạn đã chỉnh sửa (ví dụ: `app/`, `database/`, `routes/`, `config/`), chỉ copy nếu thiếu.

4. **Cài lại các dependency:**
   ```sh
   composer install
   ```

5. **Kiểm tra lại thư mục gốc đã có file `artisan` và các thư mục hệ thống.**

6. **Tiếp tục các bước cấu hình như hướng dẫn phía trên.**

> **Lưu ý:** Nếu bạn đã có code tùy chỉnh, chỉ copy các file hệ thống bị thiếu, không ghi đè code của bạn.