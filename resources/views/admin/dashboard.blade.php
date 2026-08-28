<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $totalUsers = \Illuminate\Support\Facades\DB::table('users')->count();
                $softwareCount = \Illuminate\Support\Facades\DB::table('products')->count();
                $gamesCount = \Illuminate\Support\Facades\DB::table('games')->count();
                $cvTemplateCount = \Illuminate\Support\Facades\DB::table('cv_templates')->count();
                $totalProducts = $softwareCount + $gamesCount + $cvTemplateCount;

                // 1. CV Builder Purchases & Revenue
                $paidCvCount = 0;
                $cvRevenue = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('cvs')) {
                    $paidCvCount = \Illuminate\Support\Facades\DB::table('cvs')->whereIn('status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
                    if (\Illuminate\Support\Facades\Schema::hasTable('cv_templates')) {
                        $cvRevenue = (float) \Illuminate\Support\Facades\DB::table('cvs')
                            ->leftJoin('cv_templates', 'cvs.template_id', '=', 'cv_templates.id')
                            ->whereIn('cvs.status', ['PAID', 'paid', 'SUCCESS', 'success'])
                            ->sum(\Illuminate\Support\Facades\DB::raw('COALESCE(cv_templates.price, 5000)'));
                    }
                }

                // 2. Top Up Game Purchases & Revenue
                $topupTrxCount = 0;
                $topupRevenue = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('transactions')) {
                    $topupTrxCount = \Illuminate\Support\Facades\DB::table('transactions')->whereIn('status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
                    $topupRevenue = (float) \Illuminate\Support\Facades\DB::table('transactions')->whereIn('status', ['PAID', 'paid', 'SUCCESS', 'success'])->sum('amount');
                }

                // 3. Software POS Purchases & Revenue
                $softwareOrdersCount = 0;
                $softwareRevenue = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                    $softwareOrdersCount = \Illuminate\Support\Facades\DB::table('orders')->whereIn('payment_status', ['PAID', 'paid', 'SUCCESS', 'success'])->count();
                    $softwareRevenue = (float) \Illuminate\Support\Facades\DB::table('orders')->whereIn('payment_status', ['PAID', 'paid', 'SUCCESS', 'success'])->sum('amount');
                }

                $totalPurchases = $paidCvCount + $topupTrxCount + $softwareOrdersCount;
                $totalRevenue = $cvRevenue + $topupRevenue + $softwareRevenue;

                // Real customer reviews and feedback
                $totalReviews = 0;
                $avgRating = 5.0;
                $recentFeedbacks = collect();
                $star5 = $star4 = $star3 = $star2 = $star1 = 0;
                if (\Illuminate\Support\Facades\Schema::hasTable('customer_reviews')) {
                    $totalReviews = \Illuminate\Support\Facades\DB::table('customer_reviews')->count();
                    $avgRating = $totalReviews > 0 ? round((float)\Illuminate\Support\Facades\DB::table('customer_reviews')->avg('rating'), 1) : 5.0;
                    $recentFeedbacks = \Illuminate\Support\Facades\DB::table('customer_reviews')->orderBy('id', 'desc')->take(6)->get();
                    $star5 = \Illuminate\Support\Facades\DB::table('customer_reviews')->where('rating', 5)->count();
                    $star4 = \Illuminate\Support\Facades\DB::table('customer_reviews')->where('rating', 4)->count();
                    $star3 = \Illuminate\Support\Facades\DB::table('customer_reviews')->where('rating', 3)->count();
                    $star2 = \Illuminate\Support\Facades\DB::table('customer_reviews')->where('rating', 2)->count();
                    $star1 = \Illuminate\Support\Facades\DB::table('customer_reviews')->where('rating', 1)->count();
                }

                // Maintenance Mode State
                $isMaintenance = false;
                $maintenanceMessage = 'Sistem ToTap Store sedang dalam peningkatan performa dan pemeliharaan berkala. Kami akan segera kembali!';
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $mRow = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'maintenance_mode')->first();
                    $isMaintenance = ($mRow && $mRow->value == '1');
                    $msgRow = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'maintenance_message')->first();
                    if ($msgRow && !empty($msgRow->value)) {
                        $maintenanceMessage = $msgRow->value;
                    }
                }
            @endphp

            <!-- NOTIFIKASI SUCCESS / ERROR -->
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- KONTROL STATUS SISTEM & MODE MAINTENANCE (INSTANT AJAX AUTO-UPDATE) -->
            <div x-data="{ 
                isMaint: {{ $isMaintenance ? 'true' : 'false' }},
                maintMsg: '{{ addslashes($maintenanceMessage) }}',
                toggling: false,
                openEditMsg: false,
                async toggleMaint() {
                    if (this.toggling) return;
                    const nextState = !this.isMaint;
                    const confirmMsg = nextState 
                        ? 'PERINGATAN: Mengaktifkan mode maintenance akan membuat pengunjung umum (seluruh website) tidak bisa mengakses ToTap Store. Tetap aktifkan?' 
                        : 'Apakah Anda yakin ingin MEMATIKAN mode maintenance dan membuka kembali website untuk seluruh pengunjung?';
                    
                    if (!confirm(confirmMsg)) return;

                    this.toggling = true;
                    try {
                        const res = await fetch('{{ route('admin.maintenance.toggle') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: new URLSearchParams({
                                '_token': '{{ csrf_token() }}',
                                'enabled': nextState ? '1' : '0',
                                'message': this.maintMsg
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.isMaint = data.is_maintenance;
                            alert(data.message);
                        } else {
                            window.location.reload();
                        }
                    } catch(e) {
                        window.location.reload();
                    } finally {
                        this.toggling = false;
                    }
                }
            }" class="bg-white dark:bg-gray-800 border rounded-2xl p-6 shadow-sm transition-all"
               :class="isMaint ? 'border-rose-500/40 bg-rose-500/5 dark:border-rose-500/30' : 'border-gray-200 dark:border-gray-700'">
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl flex-shrink-0 transition-colors"
                             :class="isMaint ? 'bg-rose-500/10 text-rose-500 border border-rose-500/30 shadow-lg shadow-rose-500/20' : 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/30 shadow-lg shadow-emerald-500/20'">
                            <i class="fas" :class="isMaint ? 'fa-tools animate-bounce' : 'fa-server'"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-lg font-black text-gray-900 dark:text-white">Status Sistem & Mode Maintenance</h3>
                                <template x-if="isMaint">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-rose-500 text-white shadow-md shadow-rose-500/30 animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                        Maintenance Aktif
                                    </span>
                                </template>
                                <template x-if="!isMaint">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500 text-white shadow-md shadow-emerald-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                        Website Online
                                    </span>
                                </template>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed max-w-2xl">
                                <span x-show="isMaint">
                                    <strong class="text-rose-600 dark:text-rose-400">Website sedang dikunci untuk publik.</strong> Seluruh halaman pengunjung umum diarahkan ke halaman maintenance otomatis secara real-time. Hanya akun Super Admin yang dapat mengakses sistem.
                                </span>
                                <span x-show="!isMaint">
                                    Website ToTap Store dapat diakses normal oleh semua customer dan pengunjung (Top Up Game, CV Builder, dan Software POS).
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi Toggle Maintenance -->
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <button type="button" @click="openEditMsg = !openEditMsg" 
                                class="px-4 py-2.5 rounded-xl font-bold text-xs bg-slate-100 dark:bg-gray-700 hover:bg-slate-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 transition flex items-center gap-2 shadow-sm">
                            <i class="fas fa-edit"></i>
                            <span>Pesan Maintenance</span>
                        </button>

                        <button type="button" @click="toggleMaint" :disabled="toggling"
                                class="px-5 py-2.5 rounded-xl font-black text-xs text-white shadow-lg transition transform active:scale-95 flex items-center gap-2 disabled:opacity-50"
                                :class="isMaint ? 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-600/30' : 'bg-rose-600 hover:bg-rose-500 shadow-rose-600/30'">
                            <i class="fas" :class="isMaint ? 'fa-unlock' : 'fa-lock'" x-show="!toggling"></i>
                            <i class="fas fa-spinner fa-spin" x-show="toggling"></i>
                            <span x-text="isMaint ? 'Matikan Maintenance (Buka Website)' : 'Aktifkan Mode Maintenance'"></span>
                        </button>
                    </div>
                </div>

                <!-- Form Edit Pesan Maintenance (Expandable) -->
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/60" x-show="openEditMsg" x-transition>
                    <form action="{{ route('admin.maintenance.toggle') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="enabled" :value="isMaint ? '1' : '0'">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Pesan yang tampil pada layar pengunjung saat maintenance:
                            </label>
                            <textarea name="message" x-model="maintMsg" rows="2" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-xs p-3 font-medium focus:ring-indigo-500 focus:border-indigo-500" 
                                placeholder="Tulis pesan pemeliharaan..."></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="openEditMsg = false" class="px-3.5 py-1.5 rounded-xl font-bold text-xs bg-slate-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl font-bold text-xs bg-indigo-600 hover:bg-indigo-500 text-white shadow-md transition">
                                <i class="fas fa-save mr-1"></i> Simpan Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @php
                $promoSettings = \App\Helpers\PromoHelper::getSettings();
                $promoToday = \App\Helpers\PromoHelper::isDayPromoActiveToday();
            @endphp
            <!-- PROMO & DISKON OTOMATIS STATUS BAR -->
            <div class="bg-gradient-to-r from-pink-500/10 via-purple-500/10 to-indigo-500/10 dark:from-pink-950/30 dark:via-purple-950/30 dark:to-indigo-950/30 border border-pink-500/20 dark:border-pink-800/30 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-pink-500 text-white flex items-center justify-center text-xl font-bold shadow-lg shadow-pink-500/30 shrink-0">
                        🎁
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-black text-gray-900 dark:text-white">Diskon Pengguna Baru & Promo Hari</h3>
                            @if($promoSettings['first_user_active'] || $promoSettings['day_promo_active'])
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                            @if($promoSettings['first_user_active'])
                                • Diskon Pengguna Baru: <strong>{{ $promoSettings['first_user_type'] === 'percent' ? $promoSettings['first_user_value'].'%' : 'Rp'.number_format($promoSettings['first_user_value'],0,',','.') }}</strong>
                            @endif
                            @if($promoSettings['day_promo_active'])
                                • Promo Hari: <strong>{{ $promoSettings['day_promo_type'] === 'percent' ? $promoSettings['day_promo_value'].'%' : 'Rp'.number_format($promoSettings['day_promo_value'],0,',','.') }}</strong> ({{ $promoToday['active'] ? 'Hari Ini Aktif: '.$promoToday['day_name'] : 'Hari Ini: Nonaktif' }})
                            @endif
                            @if(!$promoSettings['first_user_active'] && !$promoSettings['day_promo_active'])
                                Atur diskon pengguna baru dan potongan harga otomatis setiap hari tertentu.
                            @endif
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.promos.index') }}" class="px-5 py-2.5 rounded-xl font-black text-xs text-white bg-gradient-to-r from-pink-600 to-indigo-600 hover:opacity-90 shadow-md shadow-pink-500/20 transition flex items-center justify-center gap-1.5 whitespace-nowrap">
                    <i class="fas fa-sliders-h"></i> Atur Diskon & Promo →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Customer</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Produk</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Pembelian</h3>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($totalPurchases) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Pendapatan</h3>
                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-amber-500/10 dark:from-amber-950/40 dark:to-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-amber-300/40 dark:border-amber-700/40">
                    <h3 class="text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider mb-1">⭐ Rating Website</h3>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-amber-500">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold">/ 5.0</span>
                    </div>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 block mt-0.5">{{ $totalReviews }} ulasan pembeli</span>
                </div>
            </div>

            <!-- SECTION: Rating & Kritik/Saran Khusus Super Admin -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Star Breakdown & Satisfaction Overview -->
                <div class="lg:col-span-4 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span>⭐</span> Rating Kepuasan Website
                            </h3>
                            <a href="{{ route('admin.reviews.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                Detail →
                            </a>
                        </div>
                        
                        <div class="text-center py-4 bg-gray-50 dark:bg-gray-900/60 rounded-xl mb-5 border border-gray-100 dark:border-gray-700/60">
                            <div class="text-4xl font-black text-amber-400 font-mono">{{ number_format($avgRating, 1) }}</div>
                            <div class="flex justify-center text-amber-400 text-base my-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Rata-rata dari <strong>{{ $totalReviews }}</strong> pembeli terverifikasi</p>
                        </div>

                        <div class="space-y-2 text-xs">
                            @foreach([5 => $star5, 4 => $star4, 3 => $star3, 2 => $star2, 1 => $star1] as $s => $cnt)
                                @php
                                    $pct = $totalReviews > 0 ? round(($cnt / $totalReviews) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="w-8 font-bold text-gray-700 dark:text-gray-300">{{ $s }} ★</span>
                                    <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-amber-400 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="w-10 text-right font-mono text-gray-400">{{ $cnt }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 text-center">
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">
                            ✓ Terintegrasi Langsung Pasca-Pembayaran
                        </span>
                    </div>
                </div>

                <!-- Latest Criticisms & Suggestions Feed (Super Admin Exclusive) -->
                <div class="lg:col-span-8 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span>🔒</span> Kritik & Saran Khusus Super Admin
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Hanya dapat dilihat oleh Super Admin untuk perbaikan & evaluasi performa sistem.</p>
                        </div>
                        <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-xl transition">
                            Lihat Semua Feedback ({{ $totalReviews }}) →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentFeedbacks as $feed)
                            <div class="p-3.5 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-100 dark:border-gray-700/70 hover:border-indigo-300 dark:hover:border-indigo-700 transition">
                                <div class="flex items-start justify-between gap-3 mb-1.5">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-xs text-gray-900 dark:text-white">{{ $feed->customer_name }}</span>
                                        @if($feed->customer_contact)
                                            <span class="text-[11px] text-gray-400">({{ $feed->customer_contact }})</span>
                                        @endif
                                        <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded font-bold {{ $feed->order_type === 'cv' ? 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300' : ($feed->order_type === 'software' ? 'bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300' : 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300') }}">
                                            {{ $feed->order_type }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-amber-400 text-xs font-bold font-mono whitespace-nowrap">
                                        <span>{{ str_repeat('★', $feed->rating) }}{{ str_repeat('☆', 5 - $feed->rating) }}</span>
                                        <span class="ml-1 text-gray-500">({{ $feed->rating }}/5)</span>
                                    </div>
                                </div>

                                @if($feed->review_text)
                                    <p class="text-xs text-gray-800 dark:text-gray-200 leading-relaxed font-sans bg-white dark:bg-gray-800 p-2.5 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                                        "{{ $feed->review_text }}"
                                    </p>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">Pelanggan hanya memberikan rating bintang tanpa catatan saran.</span>
                                @endif

                                <div class="flex items-center justify-between text-[10px] text-gray-400 mt-2">
                                    <span>Produk: <strong class="text-gray-600 dark:text-gray-300">{{ $feed->product_name ?? 'Layanan' }}</strong></span>
                                    <span>{{ \Carbon\Carbon::parse($feed->created_at)->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-400 text-xs">
                                Belum ada kritik atau saran yang masuk dari pembeli.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Manage Subscriptions/Customers -->
            <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-6 sm:rounded-lg shadow-sm">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Registrasi Manual Pelanggan</h3>
                    <p class="text-sm text-gray-500">Buat akun admin secara manual jika pelanggan melakukan pembelian di luar sistem (misal transfer langsung).</p>
                </div>
                <a href="{{ route('admin.customers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                    + Tambah Pelanggan / Admin Baru
                </a>
            </div>

            <!-- Recent Orders (All Services: Software, CV Builder, Top Up Game) -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span>🧾</span> Transaksi & Pesanan Terbaru (Semua Layanan)
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mencakup seluruh transaksi CV Builder, Top Up Game, dan Software POS.</p>
                        </div>
                        <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Lihat Semua Transaksi →
                        </a>
                    </div>
                    @php
                        $recentList = collect();

                        // 1. Software Orders
                        if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                            $ords = \Illuminate\Support\Facades\DB::table('orders')
                                ->leftJoin('users', 'orders.user_id', '=', 'users.id')
                                ->leftJoin('products', 'orders.product_id', '=', 'products.id')
                                ->leftJoin('plans', 'orders.plan_id', '=', 'plans.id')
                                ->select('orders.*', 'users.name as user_name', 'products.name as product_name', 'plans.name as plan_name')
                                ->orderBy('orders.id', 'desc')
                                ->take(6)
                                ->get();
                            foreach($ords as $ord) {
                                $recentList->push((object)[
                                    'id' => $ord->order_number,
                                    'type' => 'software',
                                    'type_label' => 'Software POS',
                                    'customer' => $ord->user_name ?? 'Pelanggan POS',
                                    'item' => ($ord->product_name ?? 'Software') . ' (' . ($ord->plan_name ?? '-') . ')',
                                    'amount' => $ord->amount,
                                    'status' => $ord->payment_status,
                                    'created_at' => $ord->created_at,
                                ]);
                            }
                        }

                        // 2. CV Builder Transactions
                        if (\Illuminate\Support\Facades\Schema::hasTable('cvs')) {
                            $cvs = \Illuminate\Support\Facades\DB::table('cvs')
                                ->leftJoin('cv_templates', 'cvs.template_id', '=', 'cv_templates.id')
                                ->select('cvs.*', 'cv_templates.name as template_title')
                                ->orderBy('cvs.id', 'desc')
                                ->take(6)
                                ->get();
                            foreach($cvs as $cvItem) {
                                $recentList->push((object)[
                                    'id' => $cvItem->invoice_number ?? ('CV-' . $cvItem->id),
                                    'type' => 'cv',
                                    'type_label' => 'CV Builder',
                                    'customer' => $cvItem->name,
                                    'item' => 'Template: ' . ($cvItem->template_title ?? ($cvItem->template_name ?? 'Modern CV')),
                                    'amount' => 15000,
                                    'status' => $cvItem->status ?? 'PENDING',
                                    'created_at' => $cvItem->created_at,
                                ]);
                            }
                        }

                        // 3. Top Up Game Transactions
                        if (\Illuminate\Support\Facades\Schema::hasTable('transactions')) {
                            $trxs = \Illuminate\Support\Facades\DB::table('transactions')
                                ->leftJoin('games', 'transactions.game_id', '=', 'games.id')
                                ->leftJoin('game_products', 'transactions.game_product_id', '=', 'game_products.id')
                                ->leftJoin('users', 'transactions.user_id', '=', 'users.id')
                                ->select('transactions.*', 'games.name as game_name', 'game_products.name as product_title', 'users.name as user_name')
                                ->orderBy('transactions.id', 'desc')
                                ->take(6)
                                ->get();
                            foreach($trxs as $trx) {
                                $recentList->push((object)[
                                    'id' => $trx->invoice_number ?? ('TRX-' . $trx->id),
                                    'type' => 'topup',
                                    'type_label' => 'Top Up Game',
                                    'customer' => $trx->user_name ?? ('ID: ' . $trx->target_field_1),
                                    'item' => ($trx->game_name ?? 'Game') . ' - ' . ($trx->product_title ?? '-'),
                                    'amount' => $trx->amount,
                                    'status' => $trx->status,
                                    'created_at' => $trx->created_at,
                                ]);
                            }
                        }

                        $allRecentOrders = $recentList->sortByDesc('created_at')->take(8);
                    @endphp

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-100 dark:border-gray-700">
                                <tr>
                                    <th class="py-3 px-4">Order / Invoice ID</th>
                                    <th class="py-3 px-4">Layanan</th>
                                    <th class="py-3 px-4">Customer</th>
                                    <th class="py-3 px-4">Item / Produk</th>
                                    <th class="py-3 px-4">Nominal</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($allRecentOrders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="py-3 px-4 font-mono font-bold text-gray-900 dark:text-white">{{ $order->id }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono {{ $order->type === 'cv' ? 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300' : ($order->type === 'software' ? 'bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300' : 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300') }}">
                                                {{ $order->type_label }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">{{ $order->customer }}</td>
                                        <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ $order->item }}</td>
                                        <td class="py-3 px-4 font-mono font-bold text-gray-900 dark:text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $st = strtolower($order->status);
                                            @endphp
                                            @if($st === 'paid' || $st === 'success')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">LUNAS</span>
                                            @elseif($st === 'pending')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300">PENDING</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300">{{ strtoupper($st) }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right text-gray-400 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-400 text-xs">Belum ada transaksi di sistem.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Manage Subscriptions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">Daftar Pelanggan & Langganan (Sisa Durasi)</h3>
                    @php
                        $subs = \App\Models\Subscription::with(['user', 'product', 'plan'])->orderBy('end_date', 'desc')->get();
                    @endphp

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-4">Customer</th>
                                <th class="border-b py-2 px-4">Produk</th>
                                  <th class="border-b py-2 px-4">Paket</th>
                                  <th class="border-b py-2 px-4">Kasir / Pegawai</th>
                                <th class="border-b py-2 px-4">Sisa Durasi</th>
                                <th class="border-b py-2 px-4">Status</th>
                                <th class="border-b py-2 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subs as $sub)
                                <tr>
                                    <td class="border-b py-2 px-4 font-medium">
                                        {{ $sub->user->name }}
                                        <div class="text-xs text-gray-500">{{ $sub->user->email }}</div>
                                    </td>
                                    <td class="border-b py-2 px-4">{{ $sub->product->name }}</td>
                                      <td class="border-b py-2 px-4">
                                          @if($sub->plan)
                                              <span style="display:inline-block; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700; background:{{ strtolower($sub->plan->name) === 'pro' ? '#FEF3C7' : '#DBEAFE' }}; color:{{ strtolower($sub->plan->name) === 'pro' ? '#92400E' : '#1D4ED8' }};">
                                                  {{ strtoupper($sub->plan->name) }}
                                              </span>
                                          @else
                                              <span class="text-gray-400 text-xs">-</span>
                                          @endif
                                      </td>
                                      <td class="border-b py-2 px-4">
                                          @if($sub->product && $sub->product->slug === 'sistem-kasir-pos')
                                              @php
                                                  $kasirUser = DB::connection('kasir')->table('users')->where('email', $sub->user->email)->first();
                                                  $kasirEmployees = $kasirUser ? DB::connection('kasir')->table('users')
                                                      ->where('store_id', $kasirUser->store_id)
                                                      ->where('role', 'cashier')
                                                      ->get(['name','email']) : collect();
                                              @endphp
                                              @if($kasirEmployees->count() > 0)
                                                  @foreach($kasirEmployees as $emp)
                                                      <div class="text-xs text-gray-700 dark:text-gray-300"><span class="font-semibold">{{ $emp->name }}</span> — {{ $emp->email }}</div>
                                                  @endforeach
                                              @else
                                                  <span class="text-xs text-gray-400 italic">Belum ada kasir</span>
                                              @endif
                                          @else
                                              <span class="text-xs text-gray-400">-</span>
                                          @endif
                                      </td>
                                    <td class="border-b py-2 px-4">
                                        @if($sub->end_date && \Carbon\Carbon::parse($sub->end_date)->isFuture())
                                            <span class="text-green-600 font-semibold">{{ abs((int) \Carbon\Carbon::parse($sub->end_date)->diffInDays(now())) }} Hari Lagi</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Expired</span>
                                        @endif
                                    </td>
                                    <td class="border-b py-2 px-4">
                                        @if($sub->status === 'ACTIVE' || $sub->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                        @elseif($sub->status === 'revoked')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dicabut</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $sub->status }}</span>
                                        @endif
                                    </td>
                                    <td class="border-b py-2 px-4">
                                        @if($sub->status === 'ACTIVE' || $sub->status === 'active')
                                            <form action="{{ route('admin.customers.revoke', $sub->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mencabut akses aplikasi untuk pelanggan ini?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold transition">
                                                    Cabut Akses (Kick)
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Registered Customers List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">Daftar Akun Klien Terdaftar</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border-collapse">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2 px-4">Nama / Organisasi</th>
                                    <th class="py-2 px-4">Email</th>
                                    <th class="py-2 px-4">No. WhatsApp</th>
                                    <th class="py-2 px-4">Role</th>
                                    <th class="py-2 px-4">Password</th>
                                    <th class="py-2 px-4">Terdaftar Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allUsers = \App\Models\User::orderBy('created_at', 'desc')->get();
                                @endphp
                                @forelse($allUsers as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="border-b py-2 px-4 font-semibold">{{ $user->name }}</td>
                                        <td class="border-b py-2 px-4">{{ $user->email }}</td>
                                        <td class="border-b py-2 px-4">{{ $user->phone_number ?? '-' }}</td>
                                        <td class="border-b py-2 px-4">
                                            @if($user->role === 'superadmin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Super Admin</span>
                                            @elseif($user->role === 'admin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Admin</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Customer</span>
                                            @endif
                                        </td>
                                        <td class="border-b py-2 px-4">
                                            <span class="text-xs text-gray-400 font-mono tracking-widest cursor-help" title="Password dienkripsi (Bcrypt) demi keamanan standar internasional. Admin tidak dapat melihatnya.">********</span>
                                        </td>
                                        <td class="border-b py-2 px-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="border-b py-4 px-4 text-center text-gray-500">Belum ada user yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
