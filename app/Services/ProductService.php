<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function index($request){
        try{
            $products = Product::query();

            $products->when($request->has('search'), function($query) use ($request){
                $query->where(function($query) use ($request){
                    $query->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('slug', 'like', '%'.$request->search.'%')
                        ->orWhere('price', 'like', '%'.$request->search.'%');
                });
            });

            $products->when($request->has('sort'), function($query) use ($request){
                $order = $request->sort == 'lowest' ? 'asc' : 'desc';
                $query->orderBy('price', $order);
            });

            $products->when(!$request->has('sort'), function($query) use ($request){
                $query->orderBy('created_at', 'desc');
            });

            $products = $products->paginate(10);
            return $products;
        } catch (\Exception $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function store(array $attributes){
        try{
            DB::beginTransaction();
            if(isset($attributes['images'])){
                $images = $attributes['images'];
                $images = $this->storeImages($images);
                unset($attributes['images']);
            }
            if(isset($attributes['thumbnail'])){
                $attributes['thumbnail'] = $this->storeImage($attributes['thumbnail']);
            }
            $product = Product::create($attributes);
            if(isset($images)){
                foreach($images as $image){
                    $product->images()->create([
                        'image' => $image
                    ]);
                }
            }
            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function storeImages($images){
        try{
            if(is_array($images)){
                $paths = [];
                foreach($images as $image){
                    $paths[] = $this->storeImage($image);
                }
                return $paths;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function storeImage($image){
        try{
            $path = $image->store('public/images/products');
            return str_replace('public/', 'storage/', $path);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function show($id,array $relations=[]){
        try{
            $product = Product::with($relations)->findOrFail($id);
            return $product;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($id,array $attributes){
        try{
            DB::beginTransaction();
            $product = $this->show($id);
            if(isset($attributes['images'])){
                $images = $attributes['images'];
                $images = $this->storeImages($images);
                unset($attributes['images']);
            }
            if(isset($attributes['thumbnail'])){
                $attributes['thumbnail'] = $this->storeImage($attributes['thumbnail']);
            }
            $product->update($attributes);
            if(isset($images)){
                foreach($images as $image){
                    $product->images()->create([
                        'image' => $image
                    ]);
                }
            }
            DB::commit();

            return $product;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function destroy($id){
        try{
            $product = Product::findOrFail($id);
            $product->images()->delete();
            $product->delete();
            return $product;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
