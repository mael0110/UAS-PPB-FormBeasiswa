<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Manajemen - Poliban Scholarship') }}
        </h2>
    </x-slot>

    <div x-data="{ tab: 'pendaftaran' }" class="py-12 bg-[#FAF3DD] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm font-semibold shadow-sm">
                    ✨ {{ session('success') }}
                </div>
            @endif

            <div class="flex gap-2 mb-6 border-b border-gray-200 pb-3 data-no-print">
                <button @click="tab = 'pendaftaran'" 
                    :class="tab === 'pendaftaran' ? 'bg-[#D34E4E] text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-150 inline-flex items-center gap-2 border border-gray-200">
                    Daftar Pendaftaran Beasiswa
                </button>
                <button @click="tab = 'kelas'" 
                    :class="tab === 'kelas' ? 'bg-[#D34E4E] text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-150 inline-flex items-center gap-2 border border-gray-200">
                    Kelola Master Kelas Beasiswa
                </button>
            </div>

            <div x-show="tab === 'pendaftaran'" x-transition:enter="transition ease-out duration-200" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-[#D34E4E]">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pendaftar</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalPendaftar }} Orang</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-yellow-500">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Perlu Review</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalPending ?? 0 }} Berkas</div>
                    </div>
                    <div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-green-600">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Lolos Seleksi</div>
                        <div class="mt-1 text-2xl font-bold text-gray-900">{{ $totalDiterima }} Mahasiswa</div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-800 uppercase tracking-wide">Data Pendaftaran Masuk</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Validasi berkas kelayakan beasiswa mahasiswa Poliban di sini.</p>
                        </div>
                        <button onclick="window.print()" class="data-no-print inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                            🖨️ Cetak Laporan
                        </button>
                    </div>

                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Nama Mahasiswa / Email</th>
                                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Status Seleksi</th>
                                    <th class="px-6 py-3 text-center font-semibold text-gray-600 data-no-print">Aksi Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($allPendaftaran as $pendaftar)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $pendaftar->nama_mahasiswa ?? $pendaftar->nama }}</div>
                                        <div class="text-xs text-gray-500">{{ $pendaftar->email_mahasiswa ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider
                                            {{ $pendaftar->status_seleksi === 'Diterima' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $pendaftar->status_seleksi === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $pendaftar->status_seleksi === 'Ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $pendaftar->status_seleksi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center data-no-print">
                                        <div class="flex justify-center gap-2">
                                            <form action="{{ route('admin.update_status', $pendaftar->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_seleksi" value="Diterima">
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow-sm transition">
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.update_status', $pendaftar->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status_seleksi" value="Ditolak">
                                                <button type="submit" class="bg-[#D34E4E] hover:bg-[#a63232] text-white text-xs font-bold py-1.5 px-3 rounded shadow-sm transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-gray-400 text-center font-medium">
                                        Belum ada mahasiswa yang mengirimkan berkas pendaftaran beasiswa.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'kelas'" x-transition:enter="transition ease-out duration-200" class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-cloak>
                
                <div class="bg-white shadow-sm rounded-xl p-6 h-fit border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4">Tambah Master Kelas Coding</h3>
                    <form action="{{ route('admin.store_kelas') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Kelas / Beasiswa</label>
                            <input type="text" name="nama_kelas" required placeholder="Contoh: Web Developer Laravel" 
                                class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-[#D34E4E] focus:ring-1 focus:ring-[#D34E4E]">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Kuota Mahasiswa</label>
                            <input type="number" name="kuota" required placeholder="Contoh: 30" 
                                class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-[#D34E4E] focus:ring-1 focus:ring-[#D34E4E]">
                        </div>
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-4 rounded-lg transition duration-200 shadow-sm">
                            + Simpan Kelas Baru
                        </button>
                    </form>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 lg:col-span-2 border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-4">Daftar Master Kelas Terdaftar</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Kelas</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-600" style="width: 100px;">Kuota</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-600" style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($allKelas as $kelas)
                                <tr>
                                    <td class="px-4 py-3">
                                        <form id="update-form-{{ $kelas->id }}" action="{{ route('admin.update_kelas', $kelas->id) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" 
                                                class="w-full px-2 py-1 text-sm bg-gray-50 border border-gray-200 rounded focus:outline-none focus:border-[#D34E4E]">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                            <input type="number" name="kuota" value="{{ $kelas->kuota }}" 
                                                class="w-20 px-2 py-1 text-sm text-center bg-gray-50 border border-gray-200 rounded focus:outline-none focus:border-[#D34E4E]">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-2.5 rounded shadow-sm transition">
                                                Update
                                            </button>
                                        </form>

                                            <form action="{{ route('admin.destroy_kelas', $kelas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-[#D34E4E] hover:bg-[#a63232] text-white text-xs font-bold py-1 px-2.5 rounded shadow-sm transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-gray-400 text-center font-medium">
                                        Belum ada master data kelas coding tersedia.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        @media print {
            body { background-color: #ffffff !important; color: #000000 !important; }
            nav, header, .data-no-print { display: none !important; }
            .py-12 { padding-top: 0 !important; padding-bottom: 0 !important; }
            .bg-white { box-shadow: none !important; border: none !important; }
        }
    </style>
</x-app-layout>