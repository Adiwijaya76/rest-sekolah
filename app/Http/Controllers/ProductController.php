<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = $request->input('length', 10);
            $offset = $request->input('start', 0);
            $search = $request->input('search.value');
            $draw = intval($request->input('draw'));

            $query = Product::query()->select('id', 'name', 'price', 'created_at');

            // Total semua data (tanpa filter)
            $totalData = Product::count();

            // Filter jika ada pencarian
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%");
                });
            }

            // Total data setelah filter
            $totalFiltered = $query->count();

            // Ambil data dengan paginasi
            $data = $query
                ->orderBy('created_at', 'desc') // gunakan kolom yang valid
                ->offset($offset)
                ->limit($limit)
                ->get();

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalFiltered,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }




    public function store(Request $request)
    {
        try {
            // Validasi input
            if (!$request->name || !$request->price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Name dan price harus diisi'
                ], 400);
            }

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description ?? '';
            $product->price = $request->price;
            $product->stock = $request->stock ?? 0;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dibuat',
                'data' => $product->toArray()
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $product->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            if ($request->name) $product->name = $request->name;
            if ($request->description !== null) $product->description = $request->description;
            if ($request->price) $product->price = $request->price;
            if ($request->stock !== null) $product->stock = $request->stock;

            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diupdate',
                'data' => $product->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
