<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use \Illuminate\Validation\ValidationException;
USE \Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    public function validateSalesInput(Request $request)
    {
        $rules = [
            'sales' => 'required|array',
            'sales.*.date_sale' => 'required|date',
            'sales.*.product_id' => 'required|exists:products,id',
            'sales.*.quantity_sold' => 'required|integer|min:1',
            'sales.*.sale_price' => 'required|numeric|min:0',
        ];

        try {
            $salesInput = $request->validate($rules);
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
    
            $foodCost = $product->food_cost;
            $productName = $product->name;
    
            $profitMargin = (($salePrice - $foodCost) / $salePrice) * 100;
    
            $listProfitMargins[$productName] = round($profitMargin, 2) . "%";
        }
    
        return $listProfitMargins;
    }

    public function calculateDayMaxMinSales($salesData)
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
    
    public function calculateSalesMetrics(Request $request)
    {
        $salesInput = $this->validateSalesInput($request);
        if ($salesInput instanceof JsonResponse) {
            return $salesInput;
        }    
        
        $salesData = $salesInput['sales'];

        $listProfitMargins = $this->calculateProfitMargin($salesData);
        $listSalesPerDay = $this->calculateDayMaxMinSales($salesData);

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
}
