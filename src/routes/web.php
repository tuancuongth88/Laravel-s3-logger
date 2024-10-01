<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 's3-logger', 'namespace' => 'VehoDev\S3Logger\Http\Controller', 'middleware' => 'logs3.crud'], function () {
    Route::get('/logs', 'S3LoggerController@index');
    Route::get('/logs/{log}', 'S3LoggerController@show')->name('s3logger.show');
    Route::get('/logs/view/{folder}/{fileName}', 'S3LoggerController@showLogFile')->name('s3logger.view');
    Route::get('/download/{folder}/{file}', 'S3LoggerController@download')->name('s3logger.download');
    Route::get('/logs/synchronize/{folder}', 'S3LoggerController@synchronize')->name('s3logger.synchronize');
});
