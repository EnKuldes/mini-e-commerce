<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Datatables
use yajra\Datatables\Datatables;
use Carbon\Carbon;
// Storage
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function __construct()
    {
        can_access(\Request::path());
    }

    // Halaman
    public function pageHistory()
    {
        return view('order.history');
    }

    public function pageHome(Request $request)
    {
        $products = \App\Models\Product::when($request->has('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->search.'%');
        })->simplePaginate(10)->withQueryString();
        return view('order.home', ['products' => $products]);
    }

    public function pageCart()
    {
        
        return view('order.cart');
    }

    // Request
    public function getInformation(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric'
        ]);
        $product = \App\Models\Product::where('id', '=', $request->product_id)->firstOrFail();
        return view('order.product', ['product' => $product]);
    }

    public function getListOrders(Request $request)
    {
        $model = \App\Models\Order::select('id', 'order_date', 'total_price', 'order_status')->where('user_id', '=', \Auth::id());
        return Datatables::of($model)->setRowId('id')->addIndexColumn()->addColumn(
            'tools',
            function ($record) {
                return '<button type="button" class="btn btn-sm btn-outline-info" onclick="f_view_order(' . $record->id . ')"><i class="fas fa-glasses"></i> View</button>';
            }
        )->editColumn('order_date', function ($record) {
            return optional($record->order_date)->format('Y-m-d H:i:s');
        })->editColumn('total_price', function ($record) {
            return 'Rp. '.number_format($record->total_price,2);
        })->rawColumns(['tools'])->toJson();
    }

    public function getCart(Request $request)
    {
        try {
            $data = \App\Models\Order::where('user_id', '=', \Auth::id())->where('order_status', '=', 'created')->with(['orderDetails' => function ($query) {
                return $query->select('id', 'order_id', 'product_id', 'qty', 'current_price');
            }, 'orderDetails.product' => function ($query) {
                return $query->select('id', 'name', 'description', 'price', 'images');
            }])->firstOrFail();
            return response()->json($data, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to fetch data. '], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data not Found.'], 404);
        }

    }

    public function getDetail(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric'
        ]);

        try {
            $data = \App\Models\Order::where('id', '=', $request->order_id)->select('id', 'order_date', 'total_price', 'order_status')->with(['orderDetails' => function ($query) {
                return $query->select('id', 'order_id', 'product_id', 'qty', 'current_price');
            }, 'orderDetails.product' => function ($query) {
                return $query->select('id', 'name', 'description', 'price', 'images');
            }])->firstOrFail();
            $data->ordered_at = ($data->order_date ? "Ordered At ".$data->order_date->format('Y-m-d H:i:s') : "In Cart");
            return response()->json($data, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to fetch data. '], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data not Found.'], 404);
        }        
    }

    public function postAddCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric'
        ]);

        try {
            // cari product
            $product = \App\Models\Product::where('id', '=', $request->product_id)->firstOrFail();
            // cari Order
            $data = \App\Models\Order::firstOrCreate([
                'user_id' => \Auth::id()
                , 'order_status' => 'created'
            ]);

            // cari order detail
            $orderDetail = \App\Models\OrderDetail::firstOrCreate([
                'order_id' => $data->id
                , 'product_id' => $request->product_id
            ]);
            $orderDetail->qty += 1;
            $orderDetail->current_price = $product->price;
            $orderDetail->save();

            $total_price = 0;
            // kalkulasiin berapa totalnya
            foreach ($data->orderDetails as $record) {
                $total_price += ($record->qty*$record->current_price);
            }

            $data->total_price = $total_price;
            $data->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to fetch data. '], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data not Found.'], 404);
        }
        return response()->json(['message' => 'success'], 200);
    }

    public function postRemoveCart(Request $request)
    {
        $request->validate([
            'detail_id' => 'required|numeric'
        ]);

        try {
            // cari product
            $orderDetail = \App\Models\OrderDetail::where('id', '=', $request->detail_id)->firstOrFail();
            $orderDetail->order->total_price -= ($orderDetail->qty*$orderDetail->current_price);
            if ($orderDetail->order->total_price < 0) {
                $orderDetail->order->total_price = 0;
            }
            $orderDetail->order->save();
            $orderDetail->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to fetch data. '], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data not Found.'], 404);
        }
        return response()->json(['message' => 'success'], 200);
    }

    public function postUpdateQtyProduct(Request $request)
    {
        $request->validate([
            'detail_id' => 'required|numeric'
            , 'qty' => 'required|numeric'
        ]);

        try {
            $orderDetail = \App\Models\OrderDetail::where('id', '=', $request->detail_id)->firstOrFail();
            $diff = $request->qty - $orderDetail->qty;
            $orderDetail->order->total_price += ($diff*$orderDetail->current_price);
            $orderDetail->order->save();
            $orderDetail->qty = $request->qty;
            $orderDetail->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to fetch data. '], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data not Found.'], 404);
        }
        return response()->json(['message' => 'success'], 200);
    }

    public function postCheckout(Request $request)
    {
        try {
            $data = \App\Models\Order::where('user_id', '=', \Auth::id())->where('order_status', '=', 'created')->update([
                'order_status' => 'submitted'
                , 'order_date' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to fetch data. '], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data not Found.'], 404);
        }
        return response()->json(['message' => 'success'], 200);
    }
    
}
