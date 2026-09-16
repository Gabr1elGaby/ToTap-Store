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

        $baseAmount = (float) $request->amount;

        // Cari kode unik (1 - 499) yang belum digunakan deposit pending 60 menit terakhir
        $pendingAmounts = Deposit::where('payment_method', 'qris')
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(60))
            ->where('amount', '>=', $baseAmount)
            ->where('amount', '<=', $baseAmount + 999)
            ->pluck('amount')
            ->toArray();

        $uniqueCode = 0;
        for ($i = 1; $i <= 499; $i++) {
            $candidate = (int) $baseAmount + $i;
            if (!in_array($candidate, $pendingAmounts)) {
                $uniqueCode = $i;
                break;
            }
        }
        if ($uniqueCode === 0) {
            $uniqueCode = rand(1, 499);
        }

        $totalDepositAmount = (int) $baseAmount + $uniqueCode;
        $invoiceId = InvoiceHelper::generateDepositInvoice();
        $qrisString = QrisHelper::getDynamicQrisForAmount($totalDepositAmount);

        $deposit = Deposit::create([
            'id'             => $invoiceId,
            'user_id'        => Auth::id(),
            'amount'         => $totalDepositAmount,
            'payment_method' => 'qris',
            'status'         => 'pending',
            'snap_token'     => json_encode([
                'type'        => 'manual_qris',
                'gateway'     => 'qris_static',
                'qris_string' => $qrisString,
                'base_amount' => (int) $baseAmount,
                'unique_code' => $uniqueCode,
                'amount'      => (int) $totalDepositAmount,
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
