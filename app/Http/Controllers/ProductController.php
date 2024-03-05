<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\App;
use Redirect,Response;

class ProductController extends Controller
{

    public function __construct()
    {
    }

    public function getProducts(Request $request)
    {
      if($request->term != null){
        $term = $request->term;
        $products = Product::where(function ($query) use($term) {
                  $query->where('name', 'LIKE', "%$term%")
                        ->orWhere('description', 'LIKE', "%$term%");
                })->orderBy('id','desc')->paginate(10);
      }else {
        $products = Product::orderBy('id','desc')->paginate(10);
      }
      $response = [
          'data' => $products
      ];
      return response()->json($response);
    }

    public function destroy($productId)
    {
      $product = Product::where('id', $productId)->first();

      if($product != null){
        $product->delete();
      }

      return response()->json([
        'message' => 'Successfully removed product.',
        'status' => 200,
      ]);
    }
}