<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin - Poliban Scholarship') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#FAF3DD] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-[#C13E3E]">
                    <div class="text-xs font-bold text-gray-500 uppercase">Total Pendaftar</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalPendaftar }} Orang</div>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-yellow-500">
                    <div class="text-xs font-bold text-gray-500 uppercase">Perlu Review</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalPending }} Berkas</div>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-green-600">
                    <div class="text-xs font-bold text-gray-500 uppercase">Lolos Seleksi</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalDiterima }} Mahasiswa</div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-6">
                <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase">5 Pendaftar Beasiswa Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama Mahasiswa</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Pilihan Kelas</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pendaftaranTerbaru as $pendaftar)
                            <tr>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $pendaftar->nama_mahasiswa }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $pendaftar->nama_kelas }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-bold rounded-full {{ $pendaftar->status_seleksi === 'Diterima' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $pendaftar->status_seleksi }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-gray-500 text-center">Belum ada data pendaftaran masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>