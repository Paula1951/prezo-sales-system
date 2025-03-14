<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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

    public function calculateProfitMargin($salePrice, $foodCost)
    {
        return (($salePrice - $foodCost) / $salePrice) * 100;
    }

    public function calculateMargins(Request $request)
    {
        $salesInput = $this->validateSalesInput($request);
        
        foreach ($salesInput['sales'] as $sale) {
            $saleProductId = $sale['product_id'];
            $product = Product::find($saleProductId);
            $foodCost = $product->food_cost;
            $salePrice = $sale['sale_price'];
            $resultProfitMargin = $this->calculateProfitMargin($salePrice, $foodCost);
            
            $margins[] = [
                'product_name' => $product['name'],
                'margin' => number_format($resultProfitMargin, 2) . '%'
            ];

            $output = "Margen de beneficio de cada escandallo:\n";
            foreach ($margins as $margin) {
                $output .= "- " . $product['name'] . ": " . $margin['margin'] . "\n";
            }

            return response()->json([
                $margins
            ]);
        }
    }

    public function calculateSalesMetrics(Request $request)
    {
        return $this->calculateMargins($request);
    }
}
