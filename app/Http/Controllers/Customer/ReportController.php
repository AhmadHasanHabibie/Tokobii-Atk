<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reports = Report::with(['product.category', 'order', 'repliedBy'])
            ->where('user_id', auth()->id())->latest()->paginate(10);

        return view('customer.reports.index', compact('reports'));
    }

    public function create(Order $order, OrderItem $item): View|RedirectResponse
    {
        $this->authorizeItem($order, $item);

        if (Report::where(['user_id' => auth()->id(), 'order_id' => $order->id, 'product_id' => $item->product_id])->exists()) {
            return redirect()->route('customer.orders.show', $order)->with('warning', 'Produk ini sudah dilaporkan untuk pesanan tersebut.');
        }

        $item->load('product.category');
        return view('customer.reports.create', compact('order', 'item'));
    }

    public function store(Request $request, Order $order, OrderItem $item): RedirectResponse
    {
        $this->authorizeItem($order, $item);
        $data = $request->validate([
            'report_type' => 'required|in:damaged,not_as_described,missing,wrong_item,other',
            'description' => 'required|string|max:2000',
        ], [
            'report_type.required' => 'Jenis keluhan wajib dipilih.',
            'report_type.in' => 'Jenis keluhan yang dipilih tidak valid.',
            'description.required' => 'Rincian deskripsi masalah wajib diisi.',
            'description.string' => 'Deskripsi masalah harus berupa teks.',
            'description.max' => 'Deskripsi masalah maksimal 2000 karakter.',
        ]);

        if (Report::where(['user_id' => auth()->id(), 'order_id' => $order->id, 'product_id' => $item->product_id])->exists()) {
            return redirect()->route('customer.orders.show', $order)->with('warning', 'Produk ini sudah dilaporkan untuk pesanan tersebut.');
        }

        try {
            Report::create($data + [
                'user_id' => auth()->id(), 'order_id' => $order->id,
                'order_item_id' => $item->id, 'product_id' => $item->product_id,
            ]);
        } catch (QueryException $exception) {
            // The database unique index is the final guard against simultaneous submissions.
            return redirect()->route('customer.orders.show', $order)
                ->with('warning', 'Produk ini sudah dilaporkan untuk pesanan tersebut.');
        }

        $guidance = [
            'type' => 'success',
            'title' => 'Laporan Masalah Berhasil Dikirim!',
            'message' => 'Laporan keluhan Anda mengenai produk "' . ($item->product->name ?? 'Produk') . '" telah diterima tim support Tokobii.',
            'steps' => [
                'Administrator toko akan meninjau rincian keluhan Anda (estimasi 1x24 jam).',
                'Tanggapan dan solusi resmi dari admin akan muncul pada halaman <strong>Laporan Masalah</strong>.',
                'Jika diperlukan penggantian barang, staf toko akan menghubungi Anda via email atau nomor kontak akun.',
            ],
            'primary_btn_text' => 'Lihat Riwayat Laporan',
            'primary_btn_url' => route('customer.reports.index'),
            'secondary_btn_text' => 'Kembali ke Detail Pesanan',
            'secondary_btn_url' => route('customer.orders.show', $order),
        ];

        return redirect()->route('customer.reports.index')
            ->with('guidance', $guidance)
            ->with('success', 'Laporan produk berhasil dikirim dan menunggu balasan Admin.');
    }

    private function authorizeItem(Order $order, OrderItem $item): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->order_status === 'completed', 403, 'Laporan hanya tersedia untuk pesanan yang sudah Selesai.');
        abort_unless($item->order_id === $order->id && $item->product_id, 404);
    }
}
