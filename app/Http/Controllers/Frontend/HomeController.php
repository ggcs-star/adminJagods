<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Coupon;
use App\Models\Cuisine;
use App\Models\Discount;
use App\Models\Restaurant;
use App\Enums\RestaurantStatus;
use App\Enums\Status;
use App\Http\Controllers\FrontendController;

class HomeController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Home";
    }


    public function index()
    {
        $this->data['vouchers']               = $this->getValidVouchers();
        $this->data['cuisines']               = $this->getActiveCuisines();
        $this->data['bestSellingRestaurants'] = $this->getBestSellingRestaurants();
        $this->data['bestSellingCuisines']    = $this->getBestSellingCuisines();
        $this->data['current_data']           =  now()->format('H:i:s');
        return view('frontend.home', $this->data);
    }

    private function getValidVouchers()
    {
        $coupons = Coupon::where('to_date', '>=', now()->format('Y-m-d h:i:s'))
            ->where('from_date', '<=', now()->format('Y-m-d h:i:s'))
            ->where('restaurant_id', '!=', 0)
            ->where('limit', '>', 0)
            ->get();

        $filteredCoupons = $coupons->filter(function ($coupons) {
            $totalUsed = Discount::where('coupon_id', $coupons->id)
                ->where('status', Status::ACTIVE)
                ->count();

            return $totalUsed < $coupons->limit;
        });

        return (array) $filteredCoupons->pluck('slug', 'restaurant_id');
    }


    private function getBestSellingRestaurants()
{
    return Restaurant::with('media')
        ->where('restaurants.status', RestaurantStatus::ACTIVE)
        ->where('restaurants.current_status', RestaurantStatus::ACTIVE)
        ->select('restaurants.*')
        ->selectSub(function ($query) {
            $query->from('orders')
                ->selectRaw('COUNT(*)')
                ->whereColumn('orders.restaurant_id', 'restaurants.id');
        }, 'orders_count')
        ->selectSub(function ($query) {
            $query->from('restaurant_ratings')
                ->selectRaw('COUNT(*)')
                ->whereColumn('restaurant_ratings.restaurant_id', 'restaurants.id');
        }, 'rating_count')
        ->orderByDesc('orders_count')
        ->take(28)
        ->get();
}


    private function getBestSellingCuisines()
    {
        return $this->data['cuisines']->map(function ($cuisine) {
            $cuisine->order_counter = $cuisine->restaurants->sum(function ($restaurant) {
                return $restaurant->orders->count();
            });
            return $cuisine;
        })->sortByDesc('order_counter')->take(8);
    }

    private function getActiveCuisines()
    {
        return $this->data['cuisines'] = Cuisine::with(['restaurants.orders', 'media'])
            ->where('status', Status::ACTIVE)
            ->get();
    }
}
