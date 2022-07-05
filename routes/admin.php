<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {
    Route::group(['namespace' => 'Dashboard', 'middleware' => 'auth:admin', 'prefix' => 'admin'], function () {
        Route::get('/', 'DashboardController@index')->name('admin.dashboard'); // the first page admin visit after authentication
        Route::get('/logout', 'LoginController@logout')->name('admin.logout'); // the first page admin visit after authentication

        ############################ start  settings route######################
        Route::group(['prefix' => 'settings','middleware' => 'can:settings'], function () {
            Route::get('shipping-methods/{type}', 'SettingsController@editShippingMethods')->name('edit.shippings.methods');
            Route::put('shipping-methods/{id}', 'SettingsController@updateShippingMethods')->name('update.shippings.methods');
        });
        ############################ end  settings route######################

        ############################ start  profile route######################
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/edit', 'ProfileController@editProfile')->name('admin.profile.edit');
            Route::put('/update', 'ProfileController@updateProfile')->name('admin.profile.update');
        });
        ############################ end  profile route######################

        ############################ start  categories route######################
        Route::group(['prefix' => 'main_categories'], function () {
            Route::get('/', 'MainCategoriesController@index')->name('admin.maincategories');
            Route::get('create', 'MainCategoriesController@create')->name('admin.maincategories.create');
            Route::post('store', 'MainCategoriesController@store')->name('admin.maincategories.store');
            Route::get('edit/{id}', 'MainCategoriesController@edit')->name('admin.maincategories.edit');
            Route::post('update/{id}', 'MainCategoriesController@update')->name('admin.maincategories.update');
            Route::get('delete/{id}', 'MainCategoriesController@destroy')->name('admin.maincategories.delete');
        });
        ############################ end  categories route######################
        ################################## sub categories routes ######################################
        Route::group(['prefix' => 'sub_categories'], function () {
            Route::get('/', 'SubCategoriesController@index')->name('admin.subcategories');
            Route::get('create', 'SubCategoriesController@create')->name('admin.subcategories.create');
            Route::post('store', 'SubCategoriesController@store')->name('admin.subcategories.store');
            Route::get('edit/{id}', 'SubCategoriesController@edit')->name('admin.subcategories.edit');
            Route::post('update/{id}', 'SubCategoriesController@update')->name('admin.subcategories.update');
            Route::get('delete/{id}', 'SubCategoriesController@destroy')->name('admin.subcategories.delete');
        });
        ################################## end categories    #######################################
        ################################## brands routes ######################################
        Route::group(['prefix' => 'brands','middleware' => 'can:brands'], function () {
            Route::get('/', 'BrandsController@index')->name('admin.brands');
            Route::get('create', 'BrandsController@create')->name('admin.brands.create');
            Route::post('store', 'BrandsController@store')->name('admin.brands.store');
            Route::get('edit/{id}', 'BrandsController@edit')->name('admin.brands.edit');
            Route::post('update/{id}', 'BrandsController@update')->name('admin.brands.update');
            Route::get('delete/{id}', 'BrandsController@destroy')->name('admin.brands.delete');
        });
        ################################## end brands    #######################################
        ################################## Tags routes ######################################
        Route::group(['prefix' => 'tags' ,'middleware' => 'can:tags'], function () {
            Route::get('/', 'TagsController@index')->name('admin.tags');
            Route::get('create', 'TagsController@create')->name('admin.tags.create');
            Route::post('store', 'TagsController@store')->name('admin.tags.store');
            Route::get('edit/{id}', 'TagsController@edit')->name('admin.tags.edit');
            Route::post('update/{id}', 'TagsController@update')->name('admin.tags.update');
            Route::get('delete/{id}', 'TagsController@destroy')->name('admin.tags.delete');
        });
        ################################## end Tags    #######################################
        ################################## products routes ######################################
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', 'ProductController@index')->name('admin.products');
            Route::get('general-information', 'ProductController@create')->name('admin.products.general.create');
            Route::post('store-general-information', 'ProductController@store')->name('admin.products.general.store');

            Route::get('price/{id}', 'ProductController@getPrice')->name('admin.products.price');
            Route::post('price', 'ProductController@saveProductPrice')->name('admin.products.price.store');

            Route::get('stock/{id}', 'ProductController@getStock')->name('admin.products.stock');
            Route::post('stock', 'ProductController@saveProductStock')->name('admin.products.stock.store');

            Route::get('images/{id}', 'ProductController@addImages')->name('admin.products.images');
            Route::post('images', 'ProductController@saveProductImages')->name('admin.products.images.store');
            Route::post('images/db', 'ProductController@saveProductImagesDB')->name('admin.products.images.store.db');
        });
        ################################## end brands    #######################################
        ################################## attrributes routes ######################################
        Route::group(['prefix' => 'attributes'], function () {
            Route::get('/', 'AttributesController@index')->name('admin.attributes');
            Route::get('create', 'AttributesController@create')->name('admin.attributes.create');
            Route::post('store', 'AttributesController@store')->name('admin.attributes.store');
            Route::get('delete/{id}', 'AttributesController@destroy')->name('admin.attributes.delete');
            Route::get('edit/{id}', 'AttributesController@edit')->name('admin.attributes.edit');
            Route::post('update/{id}', 'AttributesController@update')->name('admin.attributes.update');
        });
        ################################## end attributes    #######################################
        ################################## start options ######################################
        Route::group(['prefix' => 'options'], function () {
            Route::get('/', 'OptionsController@index')->name('admin.options');
            Route::get('create', 'OptionsController@create')->name('admin.options.create');
            Route::post('store', 'OptionsController@store')->name('admin.options.store');
            //Route::get('delete/{id}','OptionsController@destroy') -> name('admin.options.delete');
            Route::get('edit/{id}', 'OptionsController@edit')->name('admin.options.edit');
            Route::post('update/{id}', 'OptionsController@update')->name('admin.options.update');
        });
        ################################## end options    #######################################
        ################################## sliders ######################################
        Route::group(['prefix' => 'sliders'], function () {
            Route::get('/', 'SliderController@addImages')->name('admin.sliders.create');
            Route::post('images', 'SliderController@saveSliderImages')->name('admin.sliders.images.store');
            Route::post('images/db', 'SliderController@saveSliderImagesDB')->name('admin.sliders.images.store.db');

        });
        ################################## end sliders    #######################################
        ################################## roles ######################################
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', 'RolesController@index')->name('admin.roles.index');
            Route::get('create', 'RolesController@create')->name('admin.roles.create');
            Route::post('store', 'RolesController@saveRole')->name('admin.roles.store');
            Route::get('/edit/{id}', 'RolesController@edit') ->name('admin.roles.edit') ;
            Route::post('update/{id}', 'RolesController@update')->name('admin.roles.update');
        });
        ################################## end roles ######################################
        /**
         * admins Routes
         */
        Route::group(['prefix' => 'users' ,'middleware' => 'can:users'], function () {
            Route::get('/', 'UsersController@index')->name('admin.users.index');
            Route::get('/create', 'UsersController@create')->name('admin.users.create');
            Route::post('/store', 'UsersController@store')->name('admin.users.store');
        });
    });
    Route::group(['namespace' => 'Dashboard', 'middleware' => 'guest:admin', 'prefix' => 'admin'], function () {
        Route::get('login', 'LoginController@login')->name('admin.login');
        Route::post('login', 'LoginController@postLogin')->name('admin.post.login');
    });
});

