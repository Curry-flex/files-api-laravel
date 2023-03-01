<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('images/{filename}', function ($filename)
{
    $file = \Illuminate\Support\Facades\Storage::get($filename);
    return response($file, 200)->header('Content-Type', 'image/jpeg');
});

Route::post('staff', [StaffController::class, 'storeStaff']);
Route::post('staffPdf', [StaffController::class, 'storeStaffPDF']);
Route::get('staff', [StaffController::class, 'staffList']);
Route::get('/staffPdf', [StaffController::class, 'getDocumentsFiles']);
Route::post('/download', [StaffController::class, 'getDownloadPdfFiles']);
Route::post('/upload-video', [StaffController::class, 'uploadVideo']);
Route::get('/upload-video', [StaffController::class, 'getvideo']);
Route::post('/upload-audio', [StaffController::class, 'uploadAudio']);
Route::get('/upload-audio', [StaffController::class, 'getaudio']);
Route::post('/upload-carousel', [CarouselController::class, 'uploadCarousel']);
Route::get('/upload-carousel', [CarouselController::class, 'getCarousel']);
Route::post('/service', [ServiceController::class, 'addService']);
Route::get('/service', [ServiceController::class, 'getServices']);
Route::get('/service/{id}', [ServiceController::class, 'getServicesById']);




