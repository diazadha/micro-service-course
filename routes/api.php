<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/mentors', 'App\Http\Controllers\MentorController@create');
Route::put('/mentors/{id}', 'App\Http\Controllers\MentorController@update');
Route::get('/mentors', 'App\Http\Controllers\MentorController@getAll');
Route::get('/mentors/{id}', 'App\Http\Controllers\MentorController@show');
Route::delete('/mentors/{id}', 'App\Http\Controllers\MentorController@delete');

Route::post('/courses', 'App\Http\Controllers\CourseController@create');
Route::put('/courses/{id}', 'App\Http\Controllers\CourseController@update');
Route::get('/courses', 'App\Http\Controllers\CourseController@getAll');
Route::get('/courses/{id}', 'App\Http\Controllers\CourseController@show');
Route::delete('/courses/{id}', 'App\Http\Controllers\CourseController@delete');

Route::post('/chapters', 'App\Http\Controllers\ChapterController@create');
Route::put('/chapters/{id}', 'App\Http\Controllers\ChapterController@update');
Route::get('/chapters', 'App\Http\Controllers\ChapterController@getAll');
Route::get('/chapters/{id}', 'App\Http\Controllers\ChapterController@show');
Route::delete('/chapters/{id}', 'App\Http\Controllers\ChapterController@delete');

Route::post('/lessons', 'App\Http\Controllers\LessonController@create');
Route::put('/lessons/{id}', 'App\Http\Controllers\LessonController@update');
Route::get('/lessons', 'App\Http\Controllers\LessonController@getAll');
Route::get('/lessons/{id}', 'App\Http\Controllers\LessonController@show');
Route::delete('/lessons/{id}', 'App\Http\Controllers\LessonController@delete');


Route::post('/imagecourses', 'App\Http\Controllers\ImageCourseController@create');
Route::delete('/imagecourses/{id}', 'App\Http\Controllers\ImageCourseController@delete');


Route::post('/mycourses', 'App\Http\Controllers\MyCourseController@create');
Route::get('/mycourses', 'App\Http\Controllers\MyCourseController@getAll');
Route::post('/mycourses/premium', 'App\Http\Controllers\MyCourseController@createPremiumAccess');


Route::post('/reviews', 'App\Http\Controllers\ReviewController@create');
Route::put('/reviews/{id}', 'App\Http\Controllers\ReviewController@update');
Route::delete('/reviews/{id}', 'App\Http\Controllers\ReviewController@delete');
