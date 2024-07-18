<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = $this->productService->index($request);
            return ProductResource::collection($products);
        }
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(ProductRequest $request)
    {
        try {
            $this->productService->store($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Product created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product creation failed', 'status' => false], 500);
        }
    }

    public function show($id)
    {
        $product = $this->productService->show($id);

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->productService->show($id);

        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $this->productService->update($id,$request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product update failed', 'status' => false], 500);
        }

    }

    public function destroy($id)
    {
        try {
            $this->productService->destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product deletion failed', 'status' => false], 500);
        }
    }
}
