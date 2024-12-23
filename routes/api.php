<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardListController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardLabelController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('v1')->group(function () {
    // Public routes (no auth required)
    Route::post('/users', [UserController::class, 'store']);
    Route::post('/users/login', [UserController::class, 'login']);

    // Protected routes (auth required)
    Route::middleware('auth:sanctum')->group(function () {
        // User Routes
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        // Board Routes
        Route::get('/boards', [BoardController::class, 'index']);
        Route::post('/boards', [BoardController::class, 'store']);
        Route::get('/boards/{board}', [BoardController::class, 'show']);
        Route::put('/boards/{board}', [BoardController::class, 'update']);
        Route::delete('/boards/{board}', [BoardController::class, 'destroy']);
        Route::post('/boards/{board}/members', [BoardController::class, 'addMember']);
        Route::delete('/boards/{board}/members/{user}', [BoardController::class, 'removeMember']);

        // Board List Routes
        Route::get('/boards/{board}/lists', [BoardListController::class, 'index']);
        Route::post('/boards/{board}/lists', [BoardListController::class, 'store']);
        Route::put('/boards/{board}/lists/{list}', [BoardListController::class, 'update']);
        Route::delete('/boards/{board}/lists/{list}', [BoardListController::class, 'destroy']);
        Route::post('/boards/{board}/lists/reorder', [BoardListController::class, 'reorder']);

        // Card Routes
        Route::post('/lists/{list}/cards', [CardController::class, 'store']);
        Route::get('/lists/{list}/cards/{card}', [CardController::class, 'show']);
        Route::put('/lists/{list}/cards/{card}', [CardController::class, 'update']);
        Route::delete('/lists/{list}/cards/{card}', [CardController::class, 'destroy']);
        Route::post('/lists/{list}/cards/reorder', [CardController::class, 'reorder']);
        Route::post('/cards/{card}/members', [CardController::class, 'addMember']);
        Route::delete('/cards/{card}/members/{user}', [CardController::class, 'removeMember']);

        // Labels
        Route::post('/cards/{card}/labels', [CardLabelController::class, 'attach']);
        Route::delete('/cards/{card}/labels/{label}', [CardLabelController::class, 'detach']);
        
        // Checklists
        Route::post('/cards/{card}/checklists', [ChecklistController::class, 'store']);
        Route::put('/cards/{card}/checklists/{checklist}', [ChecklistController::class, 'update']);
        Route::delete('/cards/{card}/checklists/{checklist}', [ChecklistController::class, 'destroy']);
        Route::post('/cards/{card}/checklists/{checklist}/items', [ChecklistItemController::class, 'store']);
        Route::put('/cards/{card}/checklists/{checklist}/items/{item}', [ChecklistItemController::class, 'update']);
        Route::delete('/cards/{card}/checklists/{checklist}/items/{item}', [ChecklistItemController::class, 'destroy']);
        
        // Attachments
        Route::post('/cards/{card}/attachments', [AttachmentController::class, 'store']);
        Route::delete('/cards/{card}/attachments/{attachment}', [AttachmentController::class, 'destroy']);
        
        // Comments
        Route::post('/cards/{card}/comments', [CommentController::class, 'store']);
        Route::put('/cards/{card}/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/cards/{card}/comments/{comment}', [CommentController::class, 'destroy']);
    });
});