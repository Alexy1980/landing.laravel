<?php

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
// в данном файле роуты группы 'web' middleware

/*Route::get('/', function () {
    return view('welcome');

});*/

Route::match(['get','post'],'/',['uses'=>'IndexController@execute','as'=>'home']);
Route::get('/page/{alias}',['uses'=>'PageController@execute','as'=>'page']);

Route::auth();

//admin/service
// т.к. раздел закрытый, пропускаем через 'middleware'=>'auth'
Route::group(['prefix'=>'admin','middleware'=>'auth'], function() {

    //admin - главная страница
    Route::get('/',function() {

        if(view()->exists('admin.index')){
            $data = ['title' => 'Панель администратора'];
            return view('admin.index', $data);
        }

    });
    Route::resource('role_user', 'RoleController');
        //admin/pages , группа pages
    Route::group(['prefix'=>'pages'],function() {

        ///admin/pages
        Route::get('/',['uses'=>'PagesController@execute','as'=>'pages']);

        //admin/pages/add
        // отправляем запрос методом get, принимаем информацию из формы методом post
        Route::match(['get','post'],'/add',['uses'=>'PagesAddController@execute','as'=>'pagesAdd']);
        //admin/edit/2
        // для изменения контента get, для сохранения инф. в б/д post, для удаления конкретной страницы - delete
        Route::match(['get','post', 'delete'],'/edit/{page}',['uses'=>'PagesEditController@execute','as'=>'pagesEdit']);

    });

    // группа portfolios
    Route::group(['prefix'=>'portfolios'],function() {


        Route::get('/',['uses'=>'PortfoliosController@execute','as'=>'portfolio']);


        Route::match(['get','post'],'/add',['uses'=>'PortfoliosAddController@execute','as'=>'portfoliosAdd']);

        Route::match(['get','post','delete'],'/edit/{portfolios}',['uses'=>'PortfoliosEditController@execute','as'=>'portfoliosEdit']);

    });

    // группа services
    Route::group(['prefix'=>'services'],function() {


        Route::get('/',['uses'=>'ServiceController@execute','as'=>'services']);


        Route::match(['get','post'],'/add',['uses'=>'ServiceAddController@execute','as'=>'serviceAdd']);

        Route::match(['get','post','delete'],'/edit/{service}',['uses'=>'ServiceEditController@execute',
            'as'=>'serviceEdit']);

    });

});


Auth::routes();

Route::get('/home', 'IndexController@execute');
