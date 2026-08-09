<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['product', 'order'])->where('user_id', auth()->id())->latest()->paginate(10);
        return view('customer.reviews.index', compact('reviews'));
    }
    private function authorizeItem(Order $order, OrderItem $item): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->order_status === 'completed', 403, 'Review hanya tersedia untuk pesanan Completed.');
        abort_unless($item->order_id === $order->id && $item->product_id, 404);
    }

    public function create(Order $order, OrderItem $item): View|RedirectResponse
    {
        $this->authorizeItem($order, $item);
        if ($item->review()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('customer.orders.show', $order)->with('warning', 'Produk ini sudah direview.');
        }
        $item->load('product');
        return view('customer.reviews.create', compact('order', 'item'));
    }

    public function store(Request $request, Order $order, OrderItem $item): RedirectResponse
    {
        $this->authorizeItem($order, $item);
        $data = $request->validate(['rating' => 'required|integer|between:1,5', 'comment' => 'required|string|max:1000']);
        if (Review::where(['user_id' => auth()->id(), 'order_id' => $order->id, 'order_item_id' => $item->id, 'product_id' => $item->product_id])->exists()) {
            return back()->with('error', 'Produk ini sudah direview untuk pesanan tersebut.');
        }
        Review::create($data + ['user_id' => auth()->id(), 'order_id' => $order->id, 'order_item_id' => $item->id, 'product_id' => $item->product_id]);
        return redirect()->route('customer.orders.show', $order)->with('success', 'Review produk berhasil disimpan.');
    }

    public function edit(Review $review): View
    {
        abort_unless($review->user_id === auth()->id(), 403);
        abort_unless($review->order->user_id === auth()->id() && $review->orderItem->order_id === $review->order_id && $review->orderItem->product_id === $review->product_id, 403);
        if (!$review->canBeEdited()) {
            return redirect()->route('customer.reviews.index')->with('error', 'Review sudah melewati batas waktu edit 24 jam.');
        }
        $review->load(['product', 'order']);
        return view('customer.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === auth()->id(), 403);
        abort_unless($review->order->user_id === auth()->id() && $review->orderItem->order_id === $review->order_id && $review->orderItem->product_id === $review->product_id, 403);
        if (!$review->canBeEdited()) {
            return redirect()->route('customer.reviews.index')->with('error', 'Review sudah melewati batas waktu edit 24 jam.');
        }
        $data = $request->validate(['rating' => 'required|integer|between:1,5', 'comment' => 'required|string|max:1000']);
        $review->update($data);
        return redirect()->route('customer.reviews.index')->with('success', 'Review berhasil diperbarui.');
    }
}
