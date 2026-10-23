# Simple Voting App

Repository: https://github.com/0xyz-exe/simple-voting-app-laravel

## Screenshots

### Polls Index & Status
![Polls index](docs/screenshots/01_polls_list.png)

### Poll Details, Voting & Percentage Breakdown
![Poll detail](docs/screenshots/02_poll_detail.png)

### Create New Poll
![Create poll](docs/screenshots/03_create_poll.png)

### Welcome Page
![Welcome](docs/screenshots/04_welcome.png)

### Authentication
![Authentication](docs/screenshots/05_login.png)

## Features

- ✅ Auth (Laravel Breeze)
- ✅ Create polls with 2-10 options
- ✅ Single or multiple-choice voting
- ✅ Live results with progress bars & percentages
- ✅ Open/close polls
- ✅ Optional end date/time
- ✅ One vote per user (duplicate prevention)

## Tech Stack

Laravel 13.38.0 · Breeze 2.4 · SQLite · Tailwind CSS

## Installation

```bash
git clone https://github.com/0xyz-exe/simple-voting-app-laravel.git
cd simple-voting-app-laravel
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

**Demo:** test@example.com / password — 3 seeded polls (2 open, 1 closed)

## Schema

**polls**: id, user_id, question, description, is_active, allow_multiple, ends_at, timestamps  
**poll_options**: id, poll_id, option_text, timestamps  
**votes**: id, user_id, poll_id, poll_option_id, timestamps

## License

MIT
