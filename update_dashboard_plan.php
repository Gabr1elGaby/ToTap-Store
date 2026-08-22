<?php
$file = 'resources/views/admin/dashboard.blade.php';
$content = file_get_contents($file);

// 1. Add "Paket" column to thead
$oldThead = '<th class="border-b py-2 px-4">Produk</th>';
$newThead = '<th class="border-b py-2 px-4">Produk</th>
                                  <th class="border-b py-2 px-4">Paket</th>
                                  <th class="border-b py-2 px-4">Kasir / Pegawai</th>';
$content = str_replace($oldThead, $newThead, $content);

// 2. Add plan name and employees to each row
$oldTd = '<td class="border-b py-2 px-4">{{ $sub->product->name }}</td>';
$newTd = '<td class="border-b py-2 px-4">{{ $sub->product->name }}</td>
                                      <td class="border-b py-2 px-4">
                                          @if($sub->plan)
                                              <span class="inline-block px-2 py-0.5 text-xs font-bold rounded {{ strtolower($sub->plan->name) === \'pro\' ? \'bg-yellow-100 text-yellow-800\' : \'bg-blue-100 text-blue-700\' }}">
                                                  {{ strtoupper($sub->plan->name) }}
                                              </span>
                                          @else
                                              <span class="text-gray-400 text-xs">-</span>
                                          @endif
                                      </td>
                                      <td class="border-b py-2 px-4">
                                          @if($sub->product && $sub->product->slug === \'sistem-kasir-pos\')
                                              @php
                                                  $kasirUser = DB::connection(\'kasir\')->table(\'users\')->where(\'email\', $sub->user->email)->first();
                                                  $kasirEmployees = $kasirUser ? DB::connection(\'kasir\')->table(\'users\')
                                                      ->where(\'store_id\', $kasirUser->store_id)
                                                      ->where(\'role\', \'cashier\')
                                                      ->get([\'name\',\'email\']) : collect();
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
                                      </td>';
$content = str_replace($oldTd, $newTd, $content);

file_put_contents($file, $content);
echo "Updated dashboard with plan name and employees column.\n";
