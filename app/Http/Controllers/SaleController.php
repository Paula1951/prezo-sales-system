<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DatabaseService;

class SaleController extends Controller
{

    protected $dbService;

    public function __construct(DatabaseService $dbService)
    {
        $this->dbService = $dbService;
    }

    public function getFoodCostProduct($id)
    {
        $dbConnection = $this->dbService->getConnection();
        $allProducts = $dbConnection->query("SELECT food_cost FROM Products WHERE ROWID>$id");
        return $allProducts;
    }

    public function calculateMargins(Request $request)
    {
        $rawBody = $request->getContent();
    
        $salesInput = $request->validate([
            'sales' => 'required|array',
            'sales.*.date_sale' => 'required|date',
            'sales.*.product_id' => 'required|exists:products,id',
            'sales.*.quantity_sold' => 'required|integer',
            'sales.*.sale_price' => 'required|numeric',
        ]);

        return response()->json(['raw' => $rawBody]);
    }

    public function calculateSalesMetrics(Request $request)
    {
        return $this->calculateMargins($request);
    }
}
