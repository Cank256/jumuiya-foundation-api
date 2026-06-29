<?php

use App\Http\Controllers\Api\AnnualReportController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\TenderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Content API
|--------------------------------------------------------------------------
*/

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show'])->where('id', '[0-9]+');

Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']); // accepts numeric id OR slug

Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/{uuid}', [JobController::class, 'show'])->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

Route::get('/tenders', [TenderController::class, 'index']);
Route::get('/tenders/{id}', [TenderController::class, 'show'])->where('id', '[0-9]+');

Route::get('/annual-reports', [AnnualReportController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Form Submission Endpoints
|--------------------------------------------------------------------------
*/

Route::post('/contact', [ContactController::class, 'store']);
Route::post('/partnership-enquiry', [ContactController::class, 'partnership']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);

/*
|--------------------------------------------------------------------------
| Analytics  — rate-limited, no auth required
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:120,1')->group(function () {
    Route::post('/analytics/event', [AnalyticsController::class, 'event']);
    Route::post('/analytics/error', [AnalyticsController::class, 'error']);
});
