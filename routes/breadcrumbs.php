<?php

use App\Models\Category;
use App\Models\Product;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Home > Category
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, Category $category) {
    if ($category->parent_id !== 0 && $category->parentCategory) {
        $trail->parent('category', $category->parentCategory);
    } else {
        $trail->parent('home');
    }
    $trail->push($category->name, route('products.category', $category->slug));
});
// Category
Breadcrumbs::for('category_no_home', function (BreadcrumbTrail $trail, Category $category) {
    if ($category->parent_id !== 0 && $category->parentCategory) {
        $trail->parent('category_no_home', $category->parentCategory);
    } 
    $trail->push($category->name, route('products.category', $category->slug));
});

// Home > Category
Breadcrumbs::for('category_byid', function (BreadcrumbTrail $trail, $category_id) {
    $category = Category::find($category_id);
    if ($category->parent_id !== 0 && $category->parentCategory) {
        $trail->parent('category', $category->parentCategory);
    } else {
        $trail->parent('home');
    }
    $trail->push($category->name, route('products.category', $category->slug));
});

// Product
Breadcrumbs::for('product', function (BreadcrumbTrail $trail, Product $product) {
    $trail->parent('category', $product->category);
    $trail->push($product->name, route('product', $product->slug));
});

// Product
Breadcrumbs::for('product_admin', function (BreadcrumbTrail $trail, Product $product) {
    $trail->parent('category_no_home', $product->category);
});
