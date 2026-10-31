<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - ToTap Store</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #0b1120;
            color: #e2e8f0;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1e293b;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #334155;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            padding: 28px 24px;
            text-align: center;
            border-bottom: 2px solid #38bdf8;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            margin: 0 0 6px 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .header p {
            color: #94a3b8;
            font-size: 13px;
            margin: 0;
        }
        .content {
            padding: 28px 24px;
        }
        .badge-success {
            display: inline-block;
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.4);
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .item-box {
            background-color: #0f172a;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 20px;
            border: 1px solid #1e293b;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .item-row:last-child {
            margin-bottom: 0;
        }
        .item-label {
            color: #94a3b8;
        }
        .item-value {
            color: #f1f5f9;
            font-weight: 600;
            text-align: right;
        }
        .account-box {
            background: linear-gradient(145deg, #132742 0%, #0f1e33 100%);
            border: 1px solid #38bdf8;
            border-radius: 12px;
            padding: 18px;
            margin: 20px 0;
        }
        .account-box h3 {
            color: #38bdf8;
            font-size: 15px;
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
        }
        .account-content {
            background-color: #090d16;
            border: 1px dashed #475569;
            border-radius: 8px;
            padding: 14px;
            font-family: monospace;
            font-size: 13px;
            color: #f8fafc;
            word-break: break-all;
            white-space: pre-wrap;
            line-height: 1.5;
        }
        .btn-invoice {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            padding: 14px 24px;
            border-radius: 10px;
            margin-top: 24px;
            font-size: 14px;
        }
        .footer {
            background-color: #0f172a;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #1e293b;
        }
        .footer a {
            color: #38bdf8;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TOTAP STORE</h1>
            <p>Terima kasih atas transaksi Anda di ToTap Store</p>
        </div>

        <div class="content">
            <div style="text-align: center;">
                <span class="badge-success">✓ Transaksi Sukses</span>
                <p style="font-size: 15px; color: #cbd5e1; margin-top: 4px; margin-bottom: 20px;">
                    Halo, pesanan Anda telah berhasil diproses dan dikirimkan.
                </p>
            </div>

            <!-- Detail Transaksi -->
            <div class="item-box">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #1e293b;">
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">No. Invoice</td>
                        <td style="padding: 8px 0; color: #38bdf8; font-weight: 700; font-size: 13px; text-align: right; font-family: monospace;">
                            {{ $transaction->id }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #1e293b;">
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">Layanan / Game</td>
                        <td style="padding: 8px 0; color: #f8fafc; font-weight: 600; font-size: 13px; text-align: right;">
                            {{ $transaction->game->name ?? 'Layanan' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #1e293b;">
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">Item / Nominal</td>
                        <td style="padding: 8px 0; color: #f8fafc; font-weight: 600; font-size: 13px; text-align: right;">
                            {{ $transaction->gameProduct->name ?? 'Produk' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #1e293b;">
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">Tujuan / Target</td>
                        <td style="padding: 8px 0; color: #f8fafc; font-weight: 600; font-size: 13px; text-align: right;">
                            {{ $transaction->target_field_1 }} {{ $transaction->target_field_2 ? '('.$transaction->target_field_2.')' : '' }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #1e293b;">
                        <td style="padding: 8px 0; color: #94a3b8; font-size: 13px;">Metode Bayar</td>
                        <td style="padding: 8px 0; color: #f8fafc; font-weight: 600; font-size: 13px; text-align: right; text-transform: uppercase;">
                            {{ $transaction->payment_method ?? 'QRIS' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0 4px 0; color: #94a3b8; font-size: 14px; font-weight: 600;">Total Bayar</td>
                        <td style="padding: 10px 0 4px 0; color: #34d399; font-weight: 800; font-size: 15px; text-align: right;">
                            Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Detail Akun / SN / Pengiriman -->
            @if(!empty($transaction->provider_sn) && !\App\Services\VipResellerService::isPendingSn($transaction->provider_sn))
                <div class="account-box">
                    <h3>Data Akun / Nomor Seri (SN)</h3>
                    <div class="account-content" id="sn-content">{{ $transaction->provider_sn }}</div>
                </div>
            @endif

            <!-- Tombol Cek Invoice -->
            <a href="{{ url('/transactions/' . $transaction->id . '/invoice') }}" class="btn-invoice" target="_blank">
                Lihat Invoice Resmi di Website
            </a>
        </div>

        <div class="footer">
            <p style="margin: 0 0 6px 0;">&copy; {{ date('Y') }} ToTap Store. All rights reserved.</p>
            <p style="margin: 0;">Email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
