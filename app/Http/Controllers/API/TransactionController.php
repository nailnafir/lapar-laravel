<?php

namespace App\Http\Controllers\API;

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
}
