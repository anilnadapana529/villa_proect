<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Villa;

class SearchController
{
    /**
     * Search villas by keyword
     * Endpoint: /search?keyword=...
     */
    public function search()
    {
        $keyword = $_GET["keyword"] ?? "";

        if (!$keyword || strlen($keyword) < 2) {
            return Response::json([
                "status" => false,
                "message" => "Keyword too short",
                "results" => []
            ]);
        }

        $villaModel = new Villa();
        $results = $villaModel->search($keyword);

        Response::json([
            "status" => true,
            "results" => $results
        ]);
    }
}
