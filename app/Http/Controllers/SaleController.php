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

        } catch (\Illuminate\Validation\ValidationException $e) {
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
    
        \Log::info("listProfitMargins: ");
        \Log::info($listProfitMargins);
        // return response()->json([
        //     'margen_de_beneficio' => $listProfitMargins
        // ]);
    }

    public function calculateDayMaxMinSales($salesData, $salePrice, $foodCost)
    {
        $listSalesPerDay = [];

        foreach ($salesData as $venta) {
            $date_sale = $venta['date_sale'];
            $quantity_sold = $venta['quantity_sold'];
            $sale_price = $venta['sale_price'];
            
            $totalSale = $quantity_sold * $sale_price;
        
            if (!isset($listSalesPerDay[$date_sale])) {
                $listSalesPerDay[$date_sale] = 0;
            }
            $listSalesPerDay[$date_sale] += $totalSale;
            
        }
        return $listSalesPerDay;
    }
    
    public function messageDayMaxMinSales($listSalesPerDay)
    {
        $daymaxSales = array_keys($listSalesPerDay, max($listSalesPerDay))[0];
        $dayminSales = array_keys($listSalesPerDay, min($listSalesPerDay))[0];

        $maxSales = $listSalesPerDay[$daymaxSales];
        $minSales = $listSalesPerDay[$dayminSales];

        return response()->json([
            "Día con mayor volumen de ventas:" => [
                'Día con mayor volumen de ventas:' => "$daymaxSales, $maxSales",
                'Día con menor volumen de ventas:' => "$dayminSales, $minSales",    
            ]
        ]);
    }

    public function calculateMargins(Request $request)
    {
        $salesInput = $this->validateSalesInput($request);
        $salesData = $salesInput['sales'];

        $this->calculateProfitMargin($salesData);

        foreach ($salesData as $sale) {
            $saleProductId = $sale['product_id'];
            $product = Product::find($saleProductId);
            $foodCost = $product->food_cost;
            $salePrice = $sale['sale_price'];


            $listSalesPerDay = $this->calculateDayMaxMinSales($salesData, $salePrice, $foodCost);
            $messageDayMaxMinSales = $this->messageDayMaxMinSales($listSalesPerDay);

            return response()->json([ $messageDayMaxMinSales ]);
        }
    }

    public function calculateSalesMetrics(Request $request)
    {
        return $this->calculateMargins($request);
    }
}
