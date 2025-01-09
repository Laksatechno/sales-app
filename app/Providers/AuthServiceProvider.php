<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Shipment;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();

        //view global user 
        View::composer('*', function ($view) {
            $jumlahpengiriman = Shipment::count();
            $jmlhcustomer = Customer::count();
            $jmlhfaktur = Sale::count();
            $totalsale = Sale::sum('total');
            $jmlhuser = User::where('role', 'customer')->count();
            $jmlhcs = $jmlhcustomer + $jmlhuser;
            $view->with('jumlahpengiriman', $jumlahpengiriman);
            $view->with('jmlhcustomer', $jmlhcustomer);
            $view->with('jmlhuser', $jmlhuser);
            $view->with('jmlhcs', $jmlhcs);
            $view->with('jmlhfaktur', $jmlhfaktur);
            $view->with('totalsale', $totalsale);
        });
    }
}
