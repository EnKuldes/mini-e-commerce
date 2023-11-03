<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Product;
use \App\Http\Resources\ProductResource;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Product::all();

        return $this->sendResponse(ProductResource::collection($data), 'success');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
            , 'description' => 'required'
            , 'price' => 'required|numeric'
            , 'attachments.*' => 'image|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation fail.', $validator->errors());
        }

        try {
            $data = new Product;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->price = $request->price;

            // Handling file upload disini
            $listAttachment = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $key => $file) {
                    $path = Storage::putFile('public/products', $file,);
                    $listAttachment[] = $path;
                }
            }
            $data->images = $listAttachment;
            $data->save();

            return $this->sendResponse(new ProductResource($data), 'success create product', 201);
        } catch (Exception $e) {
            return $this->sendErrorResponse('Failed to Upload Images. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendErrorResponse('Failed to save product. ', $e->getMessage(), 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = Product::findOrFail($id);
            return $this->sendResponse(new ProductResource($data), 'success get product');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendErrorResponse('Product not found.', $e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required'
            , 'description' => 'required'
            , 'price' => 'required|numeric'
            , 'attachments.*' => 'image|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation fail.', $validator->errors());
        }

        try {
            $data = Product::findOrFail($id);
            // Save data disini
            $data->name = $request->name;
            $data->description = $request->description;
            $data->price = $request->price;

            // Handling upload/replace file
            $listAttachment = $data->images;
            // Jika ada file attachment, tapi seharusnya ga ada karna metode PUT ga bisa nerima files umumnya
            if ($request->hasFile('attachments')) {
                $listAttachment = [];
                // delete foto lama
                foreach ($data->images as $path) {
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                    }
                }
                // upload foto baru
                foreach ($request->file('attachments') as $key => $file) {
                    $path = Storage::putFile('public/products', $file,);
                    $listAttachment[] = $path;
                }
            }
            $data->images = $listAttachment;
            $data->save();

            return $this->sendResponse(new ProductResource($data), 'success update product');   
        } catch (Exception $e) {
            return $this->sendErrorResponse('Failed to Upload Images. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendErrorResponse('Failed to update product. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendErrorResponse('Product not found.', $e->getMessage(), 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $data = Product::findOrFail($id);
            $data->delete();

            return $this->sendResponse([], 'success delete product');
        } catch (Exception $e) {
            return $this->sendErrorResponse('Failed to delete product. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendErrorResponse('Failed to delete product. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendErrorResponse('Product not found.', $e->getMessage(), 404);
        }
    }

    // ========
    private function sendResponse($data, $message, $successCode = 200)
    {
        return response()->json([
            'success' => true
            , 'data' => $data
            , 'message' => $message
        ], $successCode);
    }

    private function sendErrorResponse($error, $errorMessage = [], $errorCode = 404)
    {
        return response()->json([
            'success' => false
            , 'data' => $errorMessage
            , 'message' => $error
        ], $errorCode);
    }
}
