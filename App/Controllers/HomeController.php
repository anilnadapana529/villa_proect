<?php
namespace App\Controllers;

use App\Core\Response;
use App\Models\SliderModel;
use App\Models\CategoryModel;
use App\Models\ListingModel;

class HomeController
{
    /**
     * MAIN HOMEPAGE API
     * GET: /home-data
     */
    public function homeData()
    {
        try {
            $sliders    = SliderModel::all();
            $categories = CategoryModel::all();
            $listings   = ListingModel::featured();

            Response::json([
                "status"     => true,
                "sliders"    => $sliders,
                "categories" => $categories,
                "listings"   => $listings
            ]);

        } catch (\Exception $e) {

            Response::json([
                "status"  => false,
                "message" => "Failed to load home data",
                "error"   => $e->getMessage()
            ], 500);
        }
    }
}
