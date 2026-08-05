<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the customer shopping cart.
     */
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
            $totalItems += $item['qty'];
        }

        return view('customer.cart.index', compact('cart', 'subtotal', 'totalItems'));
    }

    /**
     * Add a product to the shopping cart.
     */
    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = (int) $request->input('qty', 1);

        if ($product->stock <= 0) {
            return back()->with('error', 'Maaf, stok produk ini telah habis.');
        }

        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['qty'] : 0;
        $newQty = $currentQty + $qty;

        if ($newQty > $product->stock) {
            return back()->with('error', 'Kuantitas melebihi stok yang tersedia (Sisa stok: ' . $product->stock . ').');
        }

        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'qty' => $newQty,
            'thumbnail' => $product->thumbnail,
            'stock' => $product->stock,
            'sku' => $product->sku,
        ];

        session()->put('cart', $cart);

        return redirect()->route('customer.cart.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update product quantity in cart.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
        ]);

        $productId = $request->product_id;
        $qty = (int) $request->qty;

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($product && $qty > $product->stock) {
                return back()->with('error', 'Kuantitas melebihi stok yang tersedia (Maks: ' . $product->stock . ').');
            }

            $cart[$productId]['qty'] = $qty;
            session()->put('cart', $cart);

            return back()->with('success', 'Jumlah produk berhasil diperbarui.');
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $productId = $request->product_id;
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $itemName = $cart[$productId]['name'];
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return back()->with('success', 'Produk "' . $itemName . '" berhasil dihapus dari keranjang.');
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    /**
     * Clear all items in the cart.
     */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('customer.cart.index')
            ->with('success', 'Keranjang belanja berhasil dikosongkan.');
    }
}
