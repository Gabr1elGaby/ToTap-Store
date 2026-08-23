<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = Transaction::with(['game', 'gameProduct', 'user'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('target_field_1', 'like', "%{$search}%")
                  ->orWhere('target_field_2', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('game', function ($gq) use ($search) {
                      $gq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Software / License Orders
        $orders = Order::with(['product', 'plan', 'user'])->latest()->paginate(20);

        return view('admin.transactions.index', compact('transactions', 'orders', 'search', 'status'));
    }

    public function invoice(string $id)
    {
        $transaction = Transaction::with(['game', 'gameProduct', 'user'])->where('id', $id)->first();

        if ($transaction) {
            return view('transactions.invoice', [
                'type' => 'topup',
                'data' => $transaction,
                'isAdmin' => true,
            ]);
        }

        $order = Order::with(['product', 'plan', 'user'])->where('order_number', $id)->firstOrFail();

        return view('transactions.invoice', [
            'type' => 'order',
            'data' => $order,
            'isAdmin' => true,
        ]);
    }
}
