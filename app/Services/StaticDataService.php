<?php

namespace App\Services;

use Illuminate\Support\Collection;
use stdClass;

class StaticDataService
{
    protected static $data;

    public static function loadData()
    {
        if (self::$data === null) {
            self::$data = config('static-data');
        }
        return self::$data;
    }

    /**
     * Convert array to object with properties
     */
    protected static function arrayToObject(array $data, array $relations = [])
    {
        $obj = new stdClass();
        foreach ($data as $key => $value) {
            $obj->$key = $value;
        }
        
        // Add relations
        foreach ($relations as $relationName => $relationData) {
            $obj->$relationName = $relationData;
        }
        
        return $obj;
    }

    /**
     * Get all categories
     */
    public static function getCategories()
    {
        $data = self::loadData();
        return collect($data['categories'])->map(function ($cat) {
            return self::arrayToObject($cat);
        });
    }

    /**
     * Get category by ID
     */
    public static function getCategoryById($id)
    {
        $data = self::loadData();
        $category = collect($data['categories'])->firstWhere('id', $id);
        return $category ? self::arrayToObject($category) : null;
    }

    /**
     * Get category by slug with relationships
     */
    public static function getCategoryBySlug($slug, $withRelations = [])
    {
        $data = self::loadData();
        $category = collect($data['categories'])->firstWhere('slug', $slug);
        
        if (!$category) {
            return null;
        }

        $relations = [];
        
        if (in_array('subcategories', $withRelations)) {
            $subcategories = self::getSubcategoriesByCategory($category['id'], $withRelations);
            $relations['subcategories'] = $subcategories;
        }
        
        if (in_array('items', $withRelations)) {
            $items = self::getItemsByCategory($category['id']);
            $relations['items'] = $items;
        }

        return self::arrayToObject($category, $relations);
    }

    /**
     * Get all types
     */
    public static function getTypes()
    {
        $data = self::loadData();
        return collect($data['types'])->map(function ($type) {
            return self::arrayToObject($type);
        });
    }

    /**
     * Get type by ID
     */
    public static function getTypeById($id)
    {
        $data = self::loadData();
        $type = collect($data['types'])->firstWhere('id', $id);
        return $type ? self::arrayToObject($type) : null;
    }

    /**
     * Get subcategories by category ID
     */
    public static function getSubcategoriesByCategory($categoryId, $withRelations = [])
    {
        $data = self::loadData();
        $subcategories = collect($data['subcategories'])->filter(function ($sub) use ($categoryId) {
            return $sub['category_id'] == $categoryId;
        });

        return $subcategories->map(function ($sub) use ($withRelations, $data) {
            $relations = [];
            
            if (in_array('type', $withRelations)) {
                $type = self::getTypeById($sub['type_id']);
                $relations['type'] = $type;
            }
            
            if (in_array('items', $withRelations)) {
                $items = self::getItemsBySubcategory($sub['id'], true);
                $relations['items'] = $items;
            }
            
            if (in_array('category', $withRelations)) {
                $category = self::getCategoryById($sub['category_id']);
                $relations['category'] = $category;
            }
            
            return self::arrayToObject($sub, $relations);
        })->values();
    }

    /**
     * Get subcategories by type names (for shared types like Beverage, Dessert)
     */
    public static function getSubcategoriesByTypeNames($typeNames, $withRelations = [])
    {
        $data = self::loadData();
        
        // Get type IDs
        $typeIds = collect($data['types'])
            ->filter(function ($type) use ($typeNames) {
                return in_array($type['name'], $typeNames);
            })
            ->pluck('id')
            ->toArray();
        
        $subcategories = collect($data['subcategories'])->filter(function ($sub) use ($typeIds) {
            return in_array($sub['type_id'], $typeIds);
        });

        return $subcategories->map(function ($sub) use ($withRelations, $data) {
            $relations = [];
            
            if (in_array('type', $withRelations)) {
                $type = self::getTypeById($sub['type_id']);
                $relations['type'] = $type;
            }
            
            if (in_array('items', $withRelations)) {
                $items = self::getItemsBySubcategory($sub['id'], true);
                $relations['items'] = $items;
            }
            
            if (in_array('category', $withRelations)) {
                $category = self::getCategoryById($sub['category_id']);
                $relations['category'] = $category;
            }
            
            return self::arrayToObject($sub, $relations);
        })->values();
    }

    /**
     * Get subcategory by ID with relations
     */
    public static function getSubcategoryById($id, $withRelations = [])
    {
        $data = self::loadData();
        $subcategory = collect($data['subcategories'])->firstWhere('id', $id);
        
        if (!$subcategory) {
            return null;
        }

        $relations = [];
        
        if (in_array('items', $withRelations)) {
            $items = self::getItemsBySubcategory($id, true);
            $relations['items'] = $items;
        }
        
        if (in_array('type', $withRelations)) {
            $type = self::getTypeById($subcategory['type_id']);
            $relations['type'] = $type;
        }
        
        if (in_array('category', $withRelations)) {
            $category = self::getCategoryById($subcategory['category_id']);
            $relations['category'] = $category;
        }

        return self::arrayToObject($subcategory, $relations);
    }

    /**
     * Get all subcategories with relations
     */
    public static function getAllSubcategories($withRelations = [])
    {
        $data = self::loadData();
        
        return collect($data['subcategories'])->map(function ($sub) use ($withRelations) {
            $relations = [];
            
            if (in_array('type', $withRelations)) {
                $type = self::getTypeById($sub['type_id']);
                $relations['type'] = $type;
            }
            
            if (in_array('items', $withRelations)) {
                $items = self::getItemsBySubcategory($sub['id'], true);
                $relations['items'] = $items;
                // Add items count
                $relations['items_count'] = $items->count();
            }
            
            if (in_array('category', $withRelations)) {
                $category = self::getCategoryById($sub['category_id']);
                $relations['category'] = $category;
            }
            
            return self::arrayToObject($sub, $relations);
        });
    }

    /**
     * Get items by category ID
     */
    public static function getItemsByCategory($categoryId, $activeOnly = true)
    {
        $data = self::loadData();
        $items = collect($data['items'])->filter(function ($item) use ($categoryId, $activeOnly) {
            $categoryMatch = $item['category_id'] == $categoryId;
            if ($activeOnly) {
                return $categoryMatch && $item['is_active'];
            }
            return $categoryMatch;
        });

        return $items->map(function ($item) {
            return self::arrayToObject($item);
        })->values();
    }

    /**
     * Get items by subcategory ID
     */
    public static function getItemsBySubcategory($subcategoryId, $activeOnly = true)
    {
        $data = self::loadData();
        $items = collect($data['items'])->filter(function ($item) use ($subcategoryId, $activeOnly) {
            $subcategoryMatch = $item['subcategory_id'] == $subcategoryId;
            if ($activeOnly) {
                return $subcategoryMatch && $item['is_active'];
            }
            return $subcategoryMatch;
        });

        return $items->map(function ($item) {
            return self::arrayToObject($item);
        })->values();
    }

    /**
     * Get all items
     */
    public static function getAllItems($activeOnly = false)
    {
        $data = self::loadData();
        $items = collect($data['items']);
        
        if ($activeOnly) {
            $items = $items->filter(function ($item) {
                return $item['is_active'];
            });
        }

        return $items->map(function ($item) {
            return self::arrayToObject($item);
        })->values();
    }

    /**
     * Count items, subcategories, categories, types
     */
    public static function count($entity, $where = null)
    {
        $data = self::loadData();
        
        if (!isset($data[$entity])) {
            return 0;
        }
        
        $collection = collect($data[$entity]);
        
        if ($where) {
            foreach ($where as $key => $value) {
                $collection = $collection->filter(function ($item) use ($key, $value) {
                    return $item[$key] == $value;
                });
            }
        }
        
        return $collection->count();
    }
}
