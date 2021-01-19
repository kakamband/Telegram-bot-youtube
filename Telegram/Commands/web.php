
<?php

Route::post("/".$_ENV['TELEGRAM_WEBHOOK_URL']."/webhook", "WebhookController@start");
Route::get('/', 'WelcomeController@welcome');
Route::resource('/prowivkas', 'ProwivkasController');
Route::post('/comment1/store', 'Comment1Controller@store')->name('comment1.add');
Route::resource('/ogljadis', 'OgljadisController');
Route::post('/comment2/store', 'Comment2Controller@store')->name('comment2.add');
Route::resource('/stattis', 'StattisController');
Route::post('/comment3/store', 'Comment3Controller@store')->name('comment3.add');
Route::resource('/novinis', 'NovinisController');
Route::post('/comment/store', 'CommentController@store')->name('comment.add');
Route::get('/download/{file}', 'DownloadsController@download');
Route::resource( '/newposts', 'NewpostController');
Route::post('/comment4/store', 'Comment4Controller@store')->name('comment4.add');
Route::get('porivnjati-smartfoni', 'TryController@index');
Route::get('/getUsers/{id}','TryController@getUsers');
Route::get('/getUserss/{id}','TryController@getUserss');
Route::get('/userposts', 'UserController@index');
Route::get('/userposts/usrChartPost', 'UserController@usrChartPost');
Route::get('/masters', 'MasterController@index');
Route::get('/contact', 'ContactController@show');
Route::post('/contact',  'ContactController@mailToAdmin');
Route::get('login/facebook', 'Auth\LoginController@redirectToProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/live-search/action', 'LiveSearch@action')->name('live-search.action');
Route::resource('pro-nas', 'PronasController');
Route::get('/politika-konfidencijnosti', 'KonfidencController@index');
Route::get('/statistica', 'StatisticsController@index');
Route::post('/tema/store', 'TemaController@store');
Route::get('/stats/dada', 'StatchangeController@dada');
Route::get('/stats/chartNews', 'StatchangeController@chartNews');
Route::get('/stats/chartNewss', 'StatchangeController@chartNewss');
Route::get('/stats/chartNewssaa', 'StatchangeController@chartNewssaa');
Route::get('/stats/chartNe', 'StatchangeController@chartNe');
Route::get('/stats/userOnDay', 'StatchangeController@userOnDay');
Route::get('/stats/userOll', 'StatchangeController@userOll');
Route::get('/stats/usPosts', 'StatchangeController@usPosts');
Route::get('/stats/agenn', 'StatchangeController@agenn');
Route::get('/stats/agennnn', 'StatchangeController@agennnn');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

