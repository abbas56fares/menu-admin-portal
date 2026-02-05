# Chops & Sicilia Fine

Restaurant menu and admin management system built with Laravel, Inertia, Vue, Tailwind, and Vite.

## Features

- Public menu with brand switching
- Item categories, types, and subcategories
- Admin dashboard for CRUD management
- Image upload + optimization for items and category logos
- Cart and order creation stored in the database

## Tech Stack

- Laravel
- Inertia.js + Vue
- Tailwind CSS
- Vite
- MySQL (or compatible)

## Requirements

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL (or MariaDB)

## Setup

1. Install PHP dependencies:

	```bash
	composer install
	```

2. Install JS dependencies:

	```bash
	npm install
	```

3. Configure environment:

	```bash
	cp .env.example .env
	php artisan key:generate
	```

4. Update database settings in .env, then migrate:

	```bash
	php artisan migrate
	```

5. Create the storage symlink:

	```bash
	php artisan storage:link
	```

6. Run the app:

	```bash
	npm run dev
	php artisan serve
	```

## Build for Production

```bash
npm run build
```

## Admin Access

After creating a user, set `is_admin` to 1. Example via Tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::where('email', 'admin@example.com')->update(['is_admin' => 1]);
```

Admin routes:

- /admin/dashboard
- /admin/categories
- /admin/types
- /admin/subcategories
- /admin/items

## Images

- Uploaded images are stored in storage/app/public
- Accessed via /storage (requires storage:link)
- Static assets can be placed in public/images

## Orders

Cart submissions create records in `orders` and `order_items`.
