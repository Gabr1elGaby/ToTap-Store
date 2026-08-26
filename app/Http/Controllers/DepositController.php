<?php

namespace App\Http\Controllers;

use App\Helpers\InvoiceHelper;
use App\Helpers\QrisHelper;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $deposits = Deposit::where('user_id', $user->id)->latest()->paginate(10);
        $presets = [10000, 20000, 50000, 100000, 200000, 500000];

        return view('deposit.index', compact('user', 'deposits', 'presets'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5000|max:5000000',
        ], [
            'amount.required' => 'Silakan pilih atau masukkan nominal isi saldo.',
            'amount.min' => 'Nominal isi saldo minimal adalah Rp5.000.',
            'amount.max' => 'Nominal isi saldo maksimal adalah Rp5.000.000.',
        ]);

        $amount = (float) $request->amount;
        $invoiceId = InvoiceHelper::generateDepositInvoice();
        $qrisString = QrisHelper::getDynamicQrisForAmount($amount);

        $deposit = Deposit::create([
            'id'             => $invoiceId,
            'user_id'        => Auth::id(),
            'amount'         => $amount,
            'payment_method' => 'qris',
            'status'         => 'pending',
            'snap_token'     => json_encode([
                'type'        => 'manual_qris',
                'gateway'     => 'qris_static',
                'qris_string' => $qrisString,
                'amount'      => (int) $amount,
            ]),
        ]);

        return redirect()->route('deposit.show', $deposit->id);
    }

    public function show($id)
    {
        $user = Auth::user();
        $deposit = Deposit::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $snapData = json_decode($deposit->snap_token, true) ?? [];
        $qrisString = $snapData['qris_string'] ?? QrisHelper::getDynamicQrisForAmount($deposit->amount);

        return view('deposit.show', compact('deposit', 'qrisString'));
    }

    public function statusApi($id)
    {
        $deposit = Deposit::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        return response()->json([
            'id'     => $deposit->id,
            'status' => $deposit->status,
            'paid'   => in_array($deposit->status, ['success', 'paid']),
        ]);
    }
}
