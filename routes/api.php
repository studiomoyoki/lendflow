<?php

Route::prefix('api')->group(function () {
    Route::get('nyt/best-sellers',[NewYorkTimesController::class, 'history']);
});
