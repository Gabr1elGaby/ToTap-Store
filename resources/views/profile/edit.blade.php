<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2.5">
            <i class="fas fa-user-cog text-blue-500"></i>
            {{ __('Pengaturan Akun & Profil') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Card 1: Informasi Profil & Nomor HP -->
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl rounded-3xl transition-colors duration-200">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card 2: Update Password -->
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl rounded-3xl transition-colors duration-200">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Card 3: Delete Account (Khusus User Biasa / Opsional) -->
            @if(strtolower(auth()->user()->role ?? '') !== 'superadmin')
            <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border border-red-100 dark:border-red-950/50 shadow-sm dark:shadow-xl rounded-3xl transition-colors duration-200">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
