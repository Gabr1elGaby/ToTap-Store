<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Baru Diterima</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            margin: 0;
            padding: 20px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1e293b;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
            border: 1px solid #334155;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 28px 24px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            color: #ecfdf5;
            margin: 6px 0 0 0;
            font-size: 14px;
            font-weight: 500;
        }
        .content {
            padding: 24px;
        }
        .alert-box {
            background-color: #064e3b;
            border-left: 4px solid #10b981;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #d1fae5;
            font-size: 14px;
            line-height: 1.5;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .details-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #334155;
            font-size: 14px;
        }
        .details-table td:first-child {
            color: #94a3b8;
            font-weight: 500;
            width: 38%;
        }
        .details-table td:last-child {
            color: #f8fafc;
            font-weight: 700;
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }
        .badge-process {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
        }
        .btn-action {
            display: block;
            width: 90%;
            margin: 20px auto 0 auto;
            text-align: center;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .footer {
            text-align: center;
            padding: 18px 20px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #334155;
            background-color: #0f172a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Pembayaran Berhasil Diterima!</h1>
            <p>Notifikasi Resmi Admin ToTap Store</p>
        </div>

        <div class="content">
            <div class="alert-box">
                <strong>Uang Masuk:</strong> Customer telah menyelesaikan pembayaran dan dana telah tercatat di sistem toko Anda.
            </div>

            <table class="details-table">
                @if($type === 'order')
                    {{-- Software SaaS Order --}}
                    <tr>
                        <td>Tipe Produk</td>
                        <td>Software SaaS / Lisensi</td>
                    </tr>
                    <tr>
                        <td>No. Invoice</td>
                        <td><code style="color: #60a5fa;">{{ $item->order_number ?? $item->id }}</code></td>
                    </tr>
                    <tr>
                        <td>Software / Paket</td>
                        <td>{{ $item->product->name ?? 'Software' }} ({{ $item->plan->name ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td>{{ $item->user->name ?? '-' }} ({{ $item->user->email ?? '-' }})</td>
                    </tr>
                    <tr>
                        <td>Total Dibayar</td>
                        <td style="color: #34d399; font-size: 16px;">Rp{{ number_format($item->amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Metode Bayar</td>
                        <td>{{ strtoupper($item->payment_method ?? ($item->gateway ?? 'QRIS')) }}</td>
                    </tr>
                    <tr>
                        <td>Status Akun</td>
                        <td><span class="badge badge-success">AKTIF / SELESAI</span></td>
                    </tr>
                @else
                    {{-- Top Up Game / Aplikasi Premium Transaction --}}
                    <tr>
                        <td>Tipe Produk</td>
                        <td>
                            @php
                                $gameCategory = strtolower($item->game->category ?? '');
                                $isApp = str_contains($gameCategory, 'aplikasi') || str_contains($gameCategory, 'app') || str_contains($gameCategory, 'streaming') || str_contains(strtolower($item->game->name ?? ''), 'premium');
                            @endphp
                            {{ $isApp ? 'Aplikasi Premium' : 'Top Up Game' }}
                        </td>
                    </tr>
                    <tr>
                        <td>No. Invoice</td>
                        <td><code style="color: #60a5fa;">{{ $item->id }}</code></td>
                    </tr>
                    <tr>
                        <td>Kategori / Layanan</td>
                        <td>{{ $item->game->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Item / Varian</td>
                        <td>{{ $item->gameProduct->name ?? ($item->product->name ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td>Data Target / Akun</td>
                        <td style="word-break: break-all;">
                            {{ $item->target_field_1 }}
                            @if(!empty($item->target_field_2))
                                ({{ $item->target_field_2 }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Total Dibayar</td>
                        <td style="color: #34d399; font-size: 16px;">Rp{{ number_format($item->amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Metode Bayar</td>
                        <td>{{ strtoupper($item->payment_method ?? 'QRIS') }}</td>
                    </tr>
                    <tr>
                        <td>Status Pengiriman</td>
                        <td>
                            @if(strtolower($item->status) === 'success')
                                <span class="badge badge-success">SUKSES TERKIRIM</span>
                            @else
                                <span class="badge badge-process">DIPROSES / PERLU CEK SALDO VIP</span>
                            @endif
                        </td>
                    </tr>
                    @if(!empty($item->provider_sn) && !\App\Services\VipResellerService::isPendingSn($item->provider_sn))
                        <tr>
                            <td>SN / Data Akun</td>
                            <td style="color: #facc15; font-family: monospace;">{{ $item->provider_sn }}</td>
                        </tr>
                    @endif
                @endif
            </table>

            @php
                $adminUrl = url('/admin/transactions');
            @endphp
            <a href="{{ $adminUrl }}" class="btn-action" target="_blank">
                Buka Admin Dashboard Transaksi
            </a>
        </div>

        <div class="footer">
            Pesan otomatis dikirim oleh sistem ToTap Store untuk Admin.
        </div>
    </div>
</body>
</html>
