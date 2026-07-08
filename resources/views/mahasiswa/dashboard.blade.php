<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Application') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen" style="background-color: #F9F7E8;">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-xl text-sm font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl text-sm font-semibold shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-2xl p-6 text-white mb-6 shadow-md" style="background-color: #D34E4E;">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-xl font-bold mb-1 text-white">Program Utama</h3>
                        <p class="text-xs text-white opacity-90 leading-relaxed">Temukan peluang terbaik untuk mendukung perjalanan akademik Anda di periode aktif ini.</p>
                        
                        @if($pendaftaran)
                            <div class="mt-4 inline-block bg-white text-xs font-bold px-3 py-1 rounded-full shadow-sm" style="color: #D34E4E;">
                                Status Saat Ini: <span class="uppercase tracking-wider">{{ $pendaftaran->status_seleksi }}</span>
                            </div>
                        @endif
                    </div>

                    @if($pendaftaran && $pendaftaran->foto)
                        <div class="text-center bg-white/10 p-2 rounded-xl border border-white/20">
                            <p class="text-[10px] uppercase font-bold tracking-wider mb-1 text-white/80">Berkas Anda:</p>
                            <img src="{{ asset('storage/' . $pendaftaran->foto) }}" alt="Foto Berkas" class="w-24 h-24 object-cover rounded-lg border-2 border-white shadow-sm">
                        </div>
                    @endif
                </div>
            </div>

            @if($pendaftaran)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                    <div class="flex items-center gap-2 pb-4 mb-6 border-b border-gray-100">
                        <span class="text-lg">🔍</span>
                        <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Status Validasi Pendaftaran</h4>
                    </div>

                    <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center gap-6 md:gap-2">
                        
                        <div class="hidden md:block absolute left-4 right-4 top-5 h-0.5 bg-gray-200 -z-0"></div>

                        <div class="flex md:flex-col items-center gap-3 md:text-center z-10 w-full md:w-1/3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-green-500 text-white ring-4 ring-green-100">
                                1
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-gray-800">Berkas Diajukan</h5>
                                <p class="text-[11px] text-gray-400">Data Anda berhasil disimpan</p>
                            </div>
                        </div>

                        <div class="flex md:flex-col items-center gap-3 md:text-center z-10 w-full md:w-1/3">
                            @if(in_array($pendaftaran->status_seleksi, ['Lolos Berkas', 'Diterima']))
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-green-500 text-white ring-4 ring-green-100">
                                    ✓
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-green-600">Validasi Selesai</h5>
                                    <p class="text-[11px] text-gray-500">Berkas dinyatakan VALID</p>
                                </div>
                            @elseif($pendaftaran->status_seleksi == 'Ditolak')
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-red-500 text-white ring-4 ring-red-100">
                                    ✕
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-red-600">Validasi Gagal</h5>
                                    <p class="text-[11px] text-red-400">Berkas tidak memenuhi syarat</p>
                                </div>
                            @elseif($pendaftaran->status_seleksi == 'Revisi')
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-yellow-500 text-white ring-4 ring-yellow-100 animate-pulse">
                                    ⚠️
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-yellow-600">Butuh Revisi</h5>
                                    <p class="text-[11px] text-yellow-500">Periksa kembali foto berkas Anda</p>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-blue-500 text-white ring-4 ring-blue-100 animate-bounce">
                                    ⏳
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-blue-600">Proses Validasi</h5>
                                    <p class="text-[11px] text-gray-400">Sedang diperiksa oleh Admin</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex md:flex-col items-center gap-3 md:text-center z-10 w-full md:w-1/3">
                            @if($pendaftaran->status_seleksi == 'Diterima')
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-green-600 text-white ring-4 ring-green-200">
                                    🎉
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-green-600">Selamat!</h5>
                                    <p class="text-[11px] text-gray-500">Anda resmi menerima beasiswa</p>
                                </div>
                            @elseif($pendaftaran->status_seleksi == 'Ditolak')
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-gray-300 text-gray-500">
                                    3
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-gray-400">Gagal Seleksi</h5>
                                    <p class="text-[11px] text-gray-400">Mohon coba di periode berikutnya</p>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-sm bg-gray-100 text-gray-400">
                                    3
                                </div>
                                <div>
                                    <h5 class="text-xs font-bold text-gray-400">Pengumuman Akhir</h5>
                                    <p class="text-[11px] text-gray-400">Menunggu tahap validasi selesai</p>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="mt-8 pt-4 border-t border-gray-50 text-xs">
                        @if($pendaftaran->status_seleksi == 'Pending')
                            <div class="p-3 rounded-xl bg-blue-50 text-blue-800 border border-blue-100">
                                📢 <strong>Status: Menunggu Antrean Validasi.</strong> Tim Admin Beasiswa sedang memeriksa kelayakan berkas fisik dan kecocokan data nominal penghasilan orang tua Anda.
                            </div>
                        @elseif($pendaftaran->status_seleksi == 'Lolos Berkas')
                            <div class="p-3 rounded-xl bg-green-50 text-green-800 border border-green-100">
                                ✅ <strong>Status: Berkas Lolos Validasi!</strong> Berkas Anda dinyatakan valid dan sesuai instruksi. Anda masuk ke tahap pemeringkatan kuota akhir.
                            </div>
                        @elseif($pendaftaran->status_seleksi == 'Revisi')
                            <div class="p-3 rounded-xl bg-yellow-50 text-yellow-800 border border-yellow-100">
                                ⚠️ <strong>Status: Kembalikan untuk Revisi.</strong> Foto berkas yang Anda unggah kabur atau tidak terbaca jelas. Silakan hubungi bagian administrasi jika tombol edit belum terbuka.
                            </div>
                        @elseif($pendaftaran->status_seleksi == 'Diterima')
                            <div class="p-3 rounded-xl bg-green-600 text-white shadow-sm font-medium">
                                🏆 <strong>Selamat! Anda Lolos Seleksi Utama.</strong> Dana beasiswa kuota pengembangan bakat terstruktur akan segera dicairkan ke rekening mahasiswa terdaftar.
                            </div>
                        @else
                            <div class="p-3 rounded-xl bg-red-50 text-red-800 border border-red-100">
                                ❌ <strong>Mohon Maaf.</strong> Berkas Anda ditolak karena belum memenuhi kriteria kualifikasi penskoran kuota semester ini.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <h4 class="text-xs font-bold text-gray-500 mb-4 tracking-wider uppercase">Program Tersedia</h4>

            <div class="space-y-4">
                @forelse($allKelas as $itemKelas)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition duration-200 hover:shadow-md">
                        
                        <div class="flex items-start gap-4">
                            <div class="p-3 rounded-xl font-bold text-xl shadow-xs" style="background-color: #FAF3DD; color: #CE7A5A;">
                                🎓
                            </div>
                            <div>
                                <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    Tersedia
                                </span>
                                <h3 class="text-base font-bold text-gray-800 mt-1">{{ $itemKelas->nama_kelas }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Ditujukan bagi mahasiswa aktif untuk kuota pengembangan bakat terstruktur.</p>
                                
                                <div class="flex flex-wrap gap-4 mt-3 text-[11px] text-gray-400 font-medium">
                                    <span class="flex items-center gap-1">📅 Kuota: {{ $itemKelas->kuota }} Mahasiswa</span>
                                    <span class="flex items-center gap-1">🌐 Full Coverage</span>
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-auto pt-2 md:pt-0">
                            @if(!$pendaftaran)
                                <a href="{{ route('mahasiswa.formulir', $itemKelas->id) }}" class="inline-block text-center text-white font-semibold py-2.5 px-6 rounded-xl text-xs shadow-md whitespace-nowrap transition duration-200 hover:opacity-90" style="background-color: #D34E4E; min-width: 130px;">
                                    Daftar Sekarang &rarr;
                                </a>
                            @else
                                <button disabled class="w-full md:w-auto text-center bg-gray-100 text-gray-400 font-semibold py-2.5 px-5 rounded-xl text-xs cursor-not-allowed border border-gray-200 whitespace-nowrap">
                                    Sudah Mendaftar
                                </button>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-8 text-center border border-dashed border-gray-200">
                        <p class="text-sm text-gray-400 font-medium">Belum ada program beasiswa aktif saat ini.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>