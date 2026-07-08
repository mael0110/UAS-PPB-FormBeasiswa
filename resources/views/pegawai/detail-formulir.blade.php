<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Pendaftaran Mahasiswa - ') }} {{ $pendaftaran->nama ?? $pendaftaran->nama_mahasiswa }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#FAF3DD] min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <a href="/pegawai/dashboard" class="inline-flex items-center gap-1 bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold py-2 px-4 rounded-lg shadow-sm border border-gray-200 transition">
                    ⬅️ Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white shadow-md rounded-xl p-8 border border-gray-100">
                <div class="border-b pb-4 mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide">Data Formulir Beasiswa</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Berikut adalah rincian data pendaftaran yang dikirimkan oleh mahasiswa.</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold rounded-full uppercase
                        {{ $pendaftaran->status_seleksi === 'Diterima' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $pendaftaran->status_seleksi === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $pendaftaran->status_seleksi === 'Lolos Berkas' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $pendaftaran->status_seleksi === 'Revisi' ? 'bg-orange-100 text-orange-800' : '' }}
                        {{ $pendaftaran->status_seleksi === 'Ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $pendaftaran->status_seleksi }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-8">
                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Nama Lengkap</span>
                            <span class="font-semibold text-gray-800 text-base">{{ $pendaftaran->nama ?? $pendaftaran->nama_mahasiswa }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">NISN</span>
                            <span class="text-gray-700 font-mono">{{ $pendaftaran->nisn }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Asal Sekolah</span>
                            <span class="text-gray-700">{{ $pendaftaran->asal_sekolah ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Jurusan / Program Studi</span>
                            <span class="text-gray-700">{{ $pendaftaran->jurusan ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Nama Ayah (Pekerjaan)</span>
                            <span class="text-gray-700 font-medium">
                                {{ $pendaftaran->nama_ayah ?? '-' }} ({{ $pendaftaran->pekerjaan_ayah ?? '-' }})
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Nama Ibu (Pekerjaan)</span>
                            <span class="text-gray-700 font-medium">
                                {{ $pendaftaran->nama_ibu ?? '-' }} ({{ $pendaftaran->pekerjaan_ibu ?? '-' }})
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Penghasilan Total Orang Tua</span>
                            <span class="text-[#D34E4E] font-bold text-base">Rp {{ number_format($pendaftaran->penghasilan_orang_tua, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">📄 Lampiran Berkas / Dokumen Pendukung:</h4>
                    @if($pendaftaran->foto)
                        <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 flex justify-center">
                            <img src="{{ asset('storage/' . $pendaftaran->foto) }}" alt="Berkas Mahasiswa" class="max-w-full h-auto rounded-lg shadow-xs max-h-[500px] object-contain">
                        </div>
                    @else
                        <div class="p-6 text-center text-xs text-gray-400 italic bg-gray-50 rounded-xl border border-dashed">
                            Mahasiswa tidak melampirkan berkas gambar.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>