<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaaSAdmin\SettingController;

Route::prefix('saas-admin')->group(function() {
    Route::middleware(['SaaSAdmin.apikey'])->group(function () {
        Route::post('user-update', [SettingController::class, 'userUpdate']);
        Route::post('email/setting', [SettingController::class, 'emailSetting']);
        Route::post('sms/setting', [SettingController::class, 'smsSetting']);
        Route::post('sms/active-provider', [SettingController::class, 'statusChange']);
        Route::post('sms/credit-store', [SettingController::class, 'smsCreditStore']);
        Route::get('sms/credit-destroy/{id}', [SettingController::class, 'smsCreditDestroy']);
        Route::get('usages-information', [SettingController::class, 'usageInformation']);
        Route::get('system-information', [SettingController::class, 'systemInformation']);
    });
});






