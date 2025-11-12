<?php

use Illuminate\Support\Facades\Route;
use Mydnic\VoletFeatureBoard\Http\Controllers\CommentController;
use Mydnic\VoletFeatureBoard\Http\Controllers\FeatureController;
use Mydnic\VoletFeatureBoard\Http\Controllers\VoteController;

Route::prefix('volet')->group(function () {

    Route::prefix(config('volet-feature-board.routes.prefix'))
        ->middleware(config('volet-feature-board.routes.middleware'))
        ->group(function () {
            Route::get('/features', [config('volet-feature-board.controllers.features'), 'index'])->name('volet.feature-board.features.index');
            Route::post('/features', [config('volet-feature-board.controllers.features'), 'store'])->name('volet.feature-board.features.store');
            Route::get('/features/{feature}', [config('volet-feature-board.controllers.features'), 'show'])->name('volet.feature-board.features.show');
            Route::patch('/features/{feature}', [config('volet-feature-board.controllers.features'), 'update'])->name('volet.feature-board.features.update');
            Route::post('/features/{feature}/vote', [config('volet-feature-board.controllers.votes'), 'store'])->name('volet.feature-board.features.vote');
            Route::post('/features/{feature}/comments', [config('volet-feature-board.controllers.comments'), 'store'])->name('volet.feature-board.features.comments.store');
        });

});
