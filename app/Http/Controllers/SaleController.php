<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use \Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function validateSalesInput(Request $request)
    {
        try {
            $salesInput = $request->validate([
                'sales' => 'required|array',
                'sales.*.date_sale' => 'required|date',
                'sales.*.product_id' => 'required|exists:products,id',
                'sales.*.quantity_sold' => 'required|integer',
                'sales.*.sale_price' => 'required|numeric',
            ]);
            return $salesInput;

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'The product with the provided ID does not exist in the database.',
                'message' => $e->errors(),
            ], 400);
        }
    }

    public function calculateProfitMargin($salesData)
    {
        $listProfitMargins = [];
    
        foreach ($salesData as $sale) {
            $productId = $sale['product_id'];
            $salePrice = $sale['sale_price'];
    
            $product = Product::find($productId);
    
            if (!$product) {
                continue;
            }
    
            $foodCost = $product->food_cost;
            $productName = $product->name;
    
            $profitMargin = (($salePrice - $foodCost) / $salePrice) * 100;
    
            $listProfitMargins[$productName] = round($profitMargin, 2) . "%";
        }
    
        return $listProfitMargins;
    }

    public function calculateDayMaxMinSales($salesData, $salePrice, $foodCost)
    {
        $listSalesPerDay = [];

        foreach ($salesData as $venta) {
            $dateSale = $venta['date_sale'];
            $quantitySold = $venta['quantity_sold'];
            $salePrice = $venta['sale_price'];
            
            $totalSale = $quantitySold * $salePrice;
        
            if (!isset($listSalesPerDay[$dateSale])) {
                $listSalesPerDay[$dateSale] = 0;
            }
            $listSalesPerDay[$dateSale] += $totalSale;
            
        }
        return $listSalesPerDay;
    }
    
    public function calculateMargins(Request $request)
    {
        $salesInput = $this->validateSalesInput($request);
        $salesData = $salesInput['sales'];

        $listProfitMargins = $this->calculateProfitMargin($salesData);

        foreach ($salesData as $sale) {
            $saleProductId = $sale['product_id'];
            $product = Product::find($saleProductId);
            $foodCost = $product->food_cost;
            $salePrice = $sale['sale_price'];

            $listSalesPerDay = $this->calculateDayMaxMinSales($salesData, $salePrice, $foodCost);
        }

        $daymaxSales = array_keys($listSalesPerDay, max($listSalesPerDay))[0];
        $dayminSales = array_keys($listSalesPerDay, min($listSalesPerDay))[0];

        $maxSales = $listSalesPerDay[$daymaxSales];
        $minSales = $listSalesPerDay[$dayminSales];

        return response()->json([ 
            "response" => [
                "Margen de beneficio de cada escandallo:" => $listProfitMargins,
                'Día con mayor volumen de ventas:' => "$daymaxSales, $maxSales",
                'Día con menor volumen de ventas:' => "$dayminSales, $minSales",    
            ] 
        ]);

    }

    public function calculateSalesMetrics(Request $request)
    {
        return $this->calculateMargins($request);
    }
}
