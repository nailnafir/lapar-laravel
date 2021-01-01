<?php

namespace App\Http\Controllers\API;

use Exception;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request) {

        // membuat filtering
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $food_id = $request->input('food_id');
        $status = $request->input('status');

        if ($id) {
            $transaction = Transaction::with(['food', 'user'])->find($id);

            if ($transaction) {
                return ResponseFormatter::success(
                    $transaction, 
                    'Berhasil mengambil data transaksi'
                );
            } else {
                return ResponseFormatter::error(
                    null, 
                    'Data transaksi tidak ada', 
                    404
                );
            }
        }

        // ambil transaksi milik dia saja (yang sedang login)
        $transaction = Transaction::with(['food', 'user'])->where('user_id', Auth::user()->id);

        if($food_id) {
            $transaction->where('food_id', $food_id);
        }

        if($status) {
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Berhasil ambil data list transaksi'
        );
    }

    public function update(Request $request, $id) {

        // ambil data transaksi berdasarkan id, lalu update
        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());

        return ResponseFormatter::success($transaction, 'Berhasil memperbarui transaksi');
    }

    public function checkout(Request $request) {

        // validasi pada tabel database
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required',
        ]);

        // membuat transaksi, url akan diupdate saat 
        $transaction = Transaction::create([
            'food_id' => $request->food_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'payment_url' => '',
        ]);

        // konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // panggil transaksi yang tadi dibuat
        $transaction = Transaction::with(['food', 'user'])->find($transaction->id);

        // membuat transaksi midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $transaction->id,
                'gross_amount' => (int) $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
            'enabled_payments' => ['gopay', 'bank_transfer'],
            'vtweb' => []
        ];

        // memanggil midtrans
        try {
            // ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            // mengembalikan data ke API
            return ResponseFormatter::success($transaction, 'Transaksi berhasil');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi gagal');
        }
    }
}
