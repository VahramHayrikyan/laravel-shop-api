<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api\v2',
    'prefix' => '/v2/workspaces-mgmt',
    'middleware' => [
        'access_key',
        'language',
    ],
], function () {
    // All routes go here

    Route::apiResources([
        'attributes' => 'AttributeController',
        'attribute-values' => 'AttributeValueController',
        'brands' => 'BrandController',
        'categories' => 'CategoryController',
        'products' => 'ProductController',
        'product-variants' => 'ProductVariantController',
        'product-kinds' => 'ProductKindController',
        'product-types' => 'ProductTypeController',
        'print-area-types' => 'PrintAreaTypeController',
        'care-details' => 'CareDetailController',
        'views' => 'ViewController',
    ]);
    Route::apiResource('tags', 'TagController')->except(['update']);

    Route::get('/set-locale/{locale}', 'TranslationController@setLocale');
});