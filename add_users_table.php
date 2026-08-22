<?php
$file = 'resources/views/admin/dashboard.blade.php';
$content = file_get_contents($file);

$tableHtml = <<<BLADE

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
                                    \$allUsers = \App\Models\User::orderBy('created_at', 'desc')->get();
                                @endphp
                                @forelse(\$allUsers as \$user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="border-b py-2 px-4 font-semibold">{{ \$user->name }}</td>
                                        <td class="border-b py-2 px-4">{{ \$user->email }}</td>
                                        <td class="border-b py-2 px-4">{{ \$user->phone_number ?? '-' }}</td>
                                        <td class="border-b py-2 px-4">
                                            @if(\$user->role === 'superadmin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Super Admin</span>
                                            @elseif(\$user->role === 'admin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Admin</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Customer</span>
                                            @endif
                                        </td>
                                        <td class="border-b py-2 px-4">
                                            <span class="text-xs text-gray-400 font-mono tracking-widest cursor-help" title="Password dienkripsi (Bcrypt) demi keamanan standar internasional. Admin tidak dapat melihatnya.">********</span>
                                        </td>
                                        <td class="border-b py-2 px-4 text-sm text-gray-500">{{ \$user->created_at->format('d M Y, H:i') }}</td>
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
BLADE;

$content = str_replace('        </div>
    </div>
</x-app-layout>', $tableHtml . "\n        </div>\n    </div>\n</x-app-layout>", $content);

file_put_contents($file, $content);
echo "Dashboard updated.\n";
