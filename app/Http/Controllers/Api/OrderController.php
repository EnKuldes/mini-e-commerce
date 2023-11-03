<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;
use \App\Models\Order;
use \App\Models\OrderDetail;
use \App\Http\Resources\OrderResource;
use Carbon\Carbon;

class OrderController extends Controller
{
    // List semua order transaksi
    public function index(Request $request): JsonResponse
    {
        $data = Order::where('user_id', '=', $request->user()->id)->orderByDesc('order_date')->get();
        return $this->sendResponse(OrderResource::collection($data), 'success retrieve all order');
    }
    
    // List detail order yg ada di order
    public function cart(Request $request): JsonResponse
    {
        try {
            $data = Order::where('order_status', '=', 'created')->where('user_id', '=', $request->user()->id)->with(['orderDetails', 'orderDetails.product'])->firstOrFail();
            return $this->sendResponse(new OrderResource($data), 'success retrieving cart');
        } catch (Exception $e) {
            return $this->sendErrorResponse('Fail. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendErrorResponse('Failed to get current cart. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendErrorResponse('Nothing in cart.', $e->getMessage(), 404);
        }
    }

    // checkout
    public function checkout(Request $request): JsonResponse
    {
        try {
            $data = \App\Models\Order::where('user_id', '=', $request->user()->id)->where('order_status', '=', 'created')->update([
                'order_status' => 'submitted'
                , 'order_date' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            if (!$data) {
                return $this->sendErrorResponse('Nothing in cart. ', [], 404);
            }

            return $this->sendResponse(new OrderResource($data), 'success checking out');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendErrorResponse('Failed to checkout. ', $e->getMessage(), 500);
        }
    }

    // add to cart
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|numeric'
            , 'qty' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation fail.', $validator->errors());
        }

        try {
            // cari product
            $product = \App\Models\Product::where('id', '=', $request->product_id)->firstOrFail();

            $data = Order::firstOrCreate([
                'user_id' => $request->user()->id
                , 'order_status' => 'created'
            ]);

            // cari order detail
            $orderDetail = \App\Models\OrderDetail::firstOrCreate([
                'order_id' => $data->id
                , 'product_id' => $request->product_id
            ]);
            $orderDetail->qty = $request->qty;
            $orderDetail->current_price = $product->price;
            $orderDetail->save();

            $total_price = 0;
            // kalkulasiin berapa totalnya
            foreach ($data->orderDetails as $record) {
                $total_price += ($record->qty*$record->current_price);
            }

            $data->total_price = $total_price;
            $data->save();

            return $this->sendResponse(new OrderResource($data), 'success adding to cart');
        } catch (Exception $e) {
            return $this->sendErrorResponse('Fail. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendErrorResponse('Failed to add product to current cart. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendErrorResponse('Product not found.', $e->getMessage(), 404);
        }
    }
    // remove from cart
    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'detail_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Validation fail.', $validator->errors());
        }

        try {
            // cari product
            $orderDetail = \App\Models\OrderDetail::where('id', '=', $request->detail_id)->firstOrFail();
            $orderDetail->order->total_price -= ($orderDetail->qty*$orderDetail->current_price);
            if ($orderDetail->order->total_price < 0) {
                $orderDetail->order->total_price = 0;
            }
            $orderDetail->order->save();

            // assign ke data
            $data = $orderDetail->order;

            $orderDetail->delete();

            return $this->sendResponse(new OrderResource($data), 'success removing from cart');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendErrorResponse('Failed to remove product to current cart. ', $e->getMessage(), 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendErrorResponse('Product not found.', $e->getMessage(), 404);
        }
    }

    // ======== ini seharusnya bisa gua simplekan dg jadi 1 class utama atau jadiin traits sih
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
