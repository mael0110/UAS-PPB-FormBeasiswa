<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pegawai - Poliban Scholarship') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#FAF3DD] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 data-no-print">
                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-[#D34E4E]">
                    <div class="text-xs font-bold text-gray-500 uppercase">Total Berkas Masuk</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalPendaftar }} Pendaftar</div>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-[#C5A76E]">
                    <div class="text-xs font-bold text-gray-500 uppercase">Mahasiswa Terverifikasi</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalDiterima }} Orang</div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 uppercase tracking-wide">Monitoring Status Beasiswa Mahasiswa</h3>
                    </div>
                    
                    <button onclick="window.print()" class="data-no-print inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-2.5 px-4 rounded-lg shadow-sm transition-all duration-200 ease-in-out transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0a2.25 2.25 0 0 1-2.25 2.25H8.59A2.25 2.25 0 0 1 6.34 18m11.318-4.171A2.25 2.25 0 0 0 20.25 11.63V8.654c0-.206-.03-.41-.09-.607m-16.32 4.17A2.25 2.25 0 0 1 1.5 11.63V8.654c0-.206.03-.41.09-.607m18.57 0A2.25 2.25 0 0 0 18 5.869V4.5A2.25 2.25 0 0 0 15.75 2.25h-7.5A2.25 2.25 0 0 0 6 4.5v1.369a2.25 2.25 0 0 0-2.09 2.178m16.32 0A2.25 2.25 0 0 1 18 8.654v1.346m-14 0V8.654c0-.256.043-.51.127-.75m13.746 0a2.25 2.25 0 0 0-2.25-2.25h-7.5a2.25 2.25 0 0 0-2.25 2.25m13.496 0v.011m0-.011a2.247 2.247 0 0 0-.192-.75m0 0a2.25 2.25 0 0 0-2.058-1.5h-7.5a2.25 2.25 0 0 0-2.058 1.5m0 0c-.064.24-.096.491-.096.75v.016m12.3-.016a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25M6 10.125a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" />
                        </svg>
                        <span>Cetak Laporan</span>
                    </button>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded-xl text-xs font-semibold shadow-sm data-no-print">
                        ✨ {{ session('success') }}
                    </div>
                @endif

                <style>
                    @media print {
                        body {
                            background-color: #ffffff !important;
                            color: #000000 !important;
                        }
                        nav, header, .data-no-print {
                            display: none !important;
                        }
                        .py-12 {
                            padding-top: 0 !important;
                            padding-bottom: 0 !important;
                        }
                        .bg-white {
                            box-shadow: none !important;
                            border: none !important;
                        }
                    }
                </style>

                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama Lengkap</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Beasiswa Yang Diambil</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">NISN</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-600">Status Kelayakan</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-600 data-no-print">Formulir</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-600 data-no-print">Aksi Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($allPendaftaran as $pendaftar)
                            <tr class="hover:bg-gray-50/80 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">
                                        {{ $pendaftar->nama ?? $pendaftar->nama_lengkap ?? $pendaftar->nama_mahasiswa ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">
                                        {{ $pendaftar->nama_kelas ?? $pendaftar->nama_beasiswa ?? 'Full Stuck' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-600 text-xs">
                                    <div class="font-semibold text-gray-800">NISN: {{ $pendaftar->nisn }}</div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
                                        {{ $pendaftar->status_seleksi === 'Diterima' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $pendaftar->status_seleksi === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $pendaftar->status_seleksi === 'Lolos Berkas' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $pendaftar->status_seleksi === 'Revisi' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $pendaftar->status_seleksi === 'Ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $pendaftar->status_seleksi }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center data-no-print">
                                    <a href="{{ route('pegawai.detail-formulir', $pendaftar->id) }}" class="text-xs text-[#b06f43] hover:text-[#D34E4E] font-bold inline-flex items-center gap-1 transition-colors duration-150 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg border border-amber-200">
                                        📄 Lihat Formulir
                                    </a>
                                </td>

                                <td class="px-6 py-4 text-right data-no-print">
                                    <form action="{{ route('pegawai.update-status', $pendaftar->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH') <select name="status_seleksi" class="text-xs bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#D34E4E] text-gray-700 font-medium">
                                            <option value="Pending" {{ $pendaftar->status_seleksi == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Diterima" {{ $pendaftar->status_seleksi == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="Ditolak" {{ $pendaftar->status_seleksi == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>

                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-3 py-1.5 rounded-lg text-xs shadow-sm transition duration-150">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-gray-400 text-center font-medium">
                                    Belum ada data pendaftaran mahasiswa yang masuk ke sistem.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>