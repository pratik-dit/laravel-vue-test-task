<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\App;
use Redirect,Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Product\CreateRequest;
use App\Http\Requests\Product\UpdateRequest;
use File;

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
                })->orderBy('id','desc')->paginate(5);
      }else {
        $products = Product::orderBy('id','desc')->paginate(5);
      }
      $response = [
          'data' => $products
      ];
      return response()->json($response);
    }

    public function show($product_id)
    {
      $product = Product::where('id', $product_id)->first();

      if($product == null){
        return response()->json([
          'message' => 'Product not found.',
          'status' => 401,
          'data' => null
        ]);
      }

      return response()->json([
        'message' => 'Successfully removed product.',
        'status' => 200,
        'data' => $product
      ]);
    }

    public function create(CreateRequest $request)
    {
      DB::beginTransaction();
      try {
        $data = $request->validated();
        // Image Upload
        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $imageName);
        $data['image'] = $imageName;

        Product::create($data);

        DB::commit();
      } catch (\Exception $e) {
          DB::rollback();
          return response()->json([
            'message'=>'Please try again!!',
            'status' => 400
          ]);
      }
      return response()->json([
        'message'=>'Product created Successfully!!',
        'status' => 200
      ]);
    }

    public function update(UpdateRequest $request, $product_id)
    {
      $product = Product::where('id', $product_id)->first();
      if($product == null){
        return response()->json([
          'message' => 'Product not found.',
          'status' => 401
        ]);
      }
      DB::beginTransaction();
      try {
        $data = $request->validated();
        if($request->has('product_image') && $request->product_image !='')
        {
          // Image Upload
          $imageName = time().'.'.$request->product_image->getClientOriginalExtension();
          $request->product_image->move(public_path('images'), $imageName);
          unset($data['product_image']);
          $data['image'] = $imageName;

          if (File::exists(public_path('images/'.$product->image))) {
            File::delete(public_path('images/'.$product->image));
          }
        }
        $product->update($data);

        DB::commit();
      } catch (\Exception $e) {
          DB::rollback();
          return response()->json([
            'message'=>'Please try again!!',
            'status' => 400
          ]);
      }
      return response()->json([
        'message' => 'Successfully updated product.',
        'status' => 200
      ]);
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