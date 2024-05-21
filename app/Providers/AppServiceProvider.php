<?php

namespace App\Providers;

use App\Models\CustomThread;
use App\Models\SalesCall;
use App\Models\UserBasicInfo;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Cmgmyr\Messenger\Models\Thread;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add this line to share the data with the sidebar view

        View::composer(['layouts.sidebar-v2', 'layouts.admin-sidebar-v2', 'layouts.manager-sidebar-v2', 'layouts.customer-admin-sidebar-v2', 'layouts.store-manager-sidebar-v2','layouts.office-sidebar'], function ($view) {
            $pxncount = SalesCall::where('pharmacy_prescription_audit', '=', 'Yes')->count();
            $orderbookcount = SalesCall::where('pharmacy_order_booked', '=', 'Yes')->count();


            $threads = CustomThread::getAllLatest()->get();


            $unread = 0;
            foreach ($threads as $thread) {
                $unreadCount = $thread->userUnreadMessagesCount(Auth::id());
                $unread = $unread + $unreadCount;
            };
            //$pxncount = SalesCall::where('pharmacy_prescription_audit', '=', 'Yes')->count();

            $view->with(['pxnAuditsCount' => $pxncount, 'ordersBookedCount' => $orderbookcount,'unReadMessagesCount'=> $unread]);
        });

        View::composer('layouts.app-v2', function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();
                $basic = UserBasicInfo::where('user_id', $userId)->first();
                $profileImage = $basic ? $basic->image : null;
            } else {
                $profileImage = null;
            }

            $view->with('profileImage', $profileImage);
        });


        Blade::if('notrole', function ($role) {
            return Auth::check() && !Auth::user()->hasRole($role);
        });


    }
}
