# Static Data Mode - Menu Admin Portal

## Overview

This Laravel project has been converted to work with **static data** instead of requiring a database connection. All menu data (categories, types, subcategories, and items) is now loaded from a configuration file.

## Changes Made

### 1. Static Data Configuration
- **File**: `config/static-data.php`
- Contains all menu data in PHP arrays
- Includes:
  - 3 sample categories (Pizza House, Burger Joint, Sushi Bar)
  - 3 types (Food, Beverage, Dessert)
  - 10 subcategories
  - 20 menu items with prices and descriptions

### 2. StaticDataService
- **File**: `app/Services/StaticDataService.php`
- Provides methods to access static data similar to Eloquent
- Methods include:
  - `getCategories()` - Get all categories
  - `getCategoryBySlug()` - Get category with relationships
  - `getTypes()` - Get all types
  - `getSubcategoriesByCategory()` - Get subcategories by category
  - `getItemsBySubcategory()` - Get items by subcategory
  - `count()` - Count entities

### 3. Updated Controllers
- **MenuController**: Now uses StaticDataService instead of Category/Type models
- **FrontendController**: Updated to fetch data from StaticDataService
- **OrderController**: Orders are stored in session (not database) with notification
- **SubcategoryController**: AJAX endpoint updated for static data

### 4. Disabled Features
- Admin CRUD operations (Create, Read, Update, Delete) are disabled
- Routes for categories, types, subcategories, and items management are commented out
- Model observers are disabled (no cache clearing needed)

### 5. Dashboard
- Statistics now pulled from StaticDataService
- Shows counts for categories, subcategories, types, and items

## How It Works

1. **Data Loading**: Static data is loaded from `config/static-data.php` on first access
2. **Menu Display**: Public menu pages fetch data using StaticDataService
3. **Orders**: When customers place orders, they're stored in session (not database)
4. **No Database Required**: The application runs without any database connection

## Customizing Data

To modify the menu data, edit `config/static-data.php`:

```php
'items' => [
    [
        'id' => 1,
        'name' => 'Your Item Name',
        'description' => 'Item description',
        'price' => 9.99,
        'currency' => 'USD',
        'image' => 'items/image.jpg',
        'is_active' => true,
        'category_id' => 1,
        'subcategory_id' => 1,
        'type_id' => 1,
    ],
    // Add more items...
],
```

## Running the Application

1. No database configuration needed
2. Just run: `php artisan serve`
3. Access the menu at: `http://localhost:8000`

## Limitations in Static Mode

- **No Data Persistence**: Orders and changes are not saved permanently
- **No Admin Editing**: Admin CRUD features are disabled
- **Session-Based Orders**: Orders exist only in the current session
- **Fixed Data**: Menu data is hardcoded in the config file

## Returning to Database Mode

To restore database functionality:

1. Uncomment admin routes in `routes/web.php`
2. Revert controllers to use Eloquent models
3. Re-enable observers in `app/Providers/AppServiceProvider.php`
4. Configure database in `.env` file
5. Run migrations: `php artisan migrate`

## File Changes Summary

- ✅ `config/static-data.php` - Created (static data file)
- ✅ `app/Services/StaticDataService.php` - Created (data service)
- ✅ `app/Http/Controllers/MenuController.php` - Updated
- ✅ `app/Http/Controllers/FrontendController.php` - Updated
- ✅ `app/Http/Controllers/OrderController.php` - Updated
- ✅ `app/Http/Controllers/Admin/SubcategoryController.php` - Updated
- ✅ `routes/web.php` - Updated (admin routes disabled)
- ✅ `app/Providers/AppServiceProvider.php` - Updated (observers disabled)

---

**Note**: This static mode is ideal for demonstration purposes, testing, or when you want to showcase the menu without database infrastructure.
