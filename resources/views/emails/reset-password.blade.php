<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - ToTap Store</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0f172a; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f8fafc;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #0f172a; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 520px; background-color: #1e293b; border-radius: 20px; border: 1px solid #334155; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 32px 24px; text-align: center; background: linear-gradient(180deg, rgba(37,99,235,0.15) 0%, rgba(30,41,59,0) 100%); border-bottom: 1px solid #334155;">
                            <div style="font-size: 26px; font-weight: 800; letter-spacing: 2px; color: #3b82f6; text-transform: uppercase;">
                                TOTAP STORE
                            </div>
                            <div style="font-size: 13px; color: #94a3b8; margin-top: 4px;">
                                Pusat Layanan Digital & Top Up Game
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px;">
                            <h1 style="font-size: 20px; font-weight: 700; color: #ffffff; margin: 0 0 16px 0;">
                                Halo, {{ $name }} 👋
                            </h1>
                            
                            <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1; margin: 0 0 20px 0;">
                                Kami menerima permintaan untuk mengatur ulang password akun Anda di <strong>ToTap Store</strong>.
                            </p>

                            <p style="font-size: 14px; line-height: 1.6; color: #cbd5e1; margin: 0 0 28px 0;">
                                Silakan klik tombol di bawah ini untuk membuat password baru:
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; background-color: #2563eb; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 12px; box-shadow: 0 4px 14px 0 rgba(37,99,235,0.39);">
                                            Atur Ulang Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <div style="background-color: #0f172a; border-radius: 12px; padding: 16px; border: 1px solid #334155; margin-bottom: 24px;">
                                <p style="font-size: 12px; line-height: 1.5; color: #94a3b8; margin: 0;">
                                    ⏱️ <strong>Catatan Keamanan:</strong> Tautan ini hanya berlaku selama <strong>{{ $expire }} menit</strong>. Jika Anda tidak merasa melakukan permintaan ini, Anda dapat mengabaikan email ini dengan aman.
                                </p>
                            </div>

                            <p style="font-size: 12px; line-height: 1.5; color: #64748b; margin: 0; word-break: break-all;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
                                <a href="{{ $url }}" style="color: #3b82f6; text-decoration: underline;">{{ $url }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; text-align: center; background-color: #0f172a; border-top: 1px solid #334155;">
                            <p style="font-size: 12px; color: #64748b; margin: 0 0 8px 0;">
                                © {{ date('Y') }} ToTap Store. All rights reserved.
                            </p>
                            <p style="font-size: 11px; color: #475569; margin: 0;">
                                Email ini dikirim secara otomatis, mohon untuk tidak membalas langsung ke alamat ini.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
