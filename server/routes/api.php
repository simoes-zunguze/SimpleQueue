<?php

use App\Events\QueueSendMessage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function(){

        //People
        // Route::ApiResource('people', PersonController::class);

        //Tickets
        // Route::ApiResource('tickets', TicketController::class);
        Route::patch('redirect-ticket/{queue_id}', [TicketController::class, 'redirectTicket']);
        Route::patch('close-ticket/{queue_id}', [TicketController::class, 'closeTicket']);
        Route::patch('absent-ticket/{queue_id}', [TicketController::class, 'absentTicket']);
        Route::patch('call-ticket/{queue_id}', [TicketController::class, 'callTicket']);
        Route::patch('postpone-ticket/{queue_id}', [TicketController::class, "postpone"]);

        Route::get('priorities', [PriorityController::class, 'index']);

        Route::get('logout', [AuthController::class, "logout"]);

});
Route::get('tickets-list/{id}', [TicketController::class, 'ticketList']);
Route::get('queue-summary/{id}', [TicketController::class, 'queueSummary']);
Route::post('get-ticket/{queue_id}', [TicketController::class, 'getTicket']);
Route::get('queues/{id}', [QueueController::class,'show']);
Route::get('queues', [QueueController::class,'index']);
Route::get('snoozed', [TicketController::class,'allSnoozedList']);


Route::post("/login", [AuthController::class, "login"]);
