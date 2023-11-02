<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Datatables
use yajra\Datatables\Datatables;
use Carbon\Carbon;
// Storage
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        // can_access(\Request::path());
    }

    // Halamannya
    public function index()
    {
        return view('product.index');
    }

    // Request disini
    public function getListProducts(Request $request)
    {
        $model = \App\Models\Product::query()->select('id', 'name', 'price', 'created_at')->orderByDesc('id');

        return Datatables::of($model)->setRowId('id')->addIndexColumn()->addColumn(
            'tools',
            function ($record) {
                return '<button type="button" class="btn btn-sm btn-outline-info" onclick="f_edit_form_product(' . $record->id . ')"><i class="fas fa-edit"></i> </button><button type="button" class="btn btn-sm btn-outline-danger" onclick="f_delete_form_product(' . $record->id . ')"><i class="fas fa-trash"></i> </button>';
            }
        )->rawColumns(['tools'])->toJson();
    }

    public function getProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric'
        ]);

        try {
            $data = \App\Models\Product::where('id', '=', $request->product_id)->select('id', 'name', 'price', 'images', 'description')->firstOrFail();
            return response()->json($data, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to fetch data. '], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Data not Found.'], 404);
        }
    }

    public function postSaveProduct(Request $request)
    {
        $request->validate([
            'name' => 'required'
            , 'description' => 'required'
            , 'price' => 'required'
            , 'attachments' => 'required_without:product_id'
            , 'attachments.*' => 'image|max:2048'
        ]);

        $data = null;
        try {
            if ($request->has('product_id')) {
                $data = \App\Models\Product::where('id', '=', $request->product_id)->first();
            }
            if (!$data) {
                $data = new \App\Models\Product;
            }

            $data->name = $request->name;
            $data->description = $request->description;
            $data->price = $request->price;
            
            $listAttachment = ($request->has('product_id') ? $data->images : []);
            // cek ada attachment ga
            if ($request->hasFile('attachments')) {
                if ($request->has('product_id')) {
                    foreach ($data->images as $path) {
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                    }
                }
                // cek kalo ada product id nya, hapus foto foto lama, dan upload pake foto foto baru
                foreach ($request->file('attachments') as $key => $file) {
                    $path = Storage::putFile('public/products', $file,);
                    $listAttachment[] = $path;
                }
            }
            $data->images = $listAttachment;
            $data->save();
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to Upload Images. '], 500);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to save Form. '], 500);
        }
        return response()->json(['message' => 'success'], 200);        
    }

    public function postDeleteProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric'
        ]);

        try {
            $data = \App\Models\Product::where('id', '=', $request->product_id)->first();
            foreach ($data->images as $path) {
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
            $data->delete();
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to do that. '], 500);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to Delete Product. '], 500);
        }
        return response()->json(['message' => 'success'], 200);
    }
}
