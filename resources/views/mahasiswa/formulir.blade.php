<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Pendaftaran Beasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen" style="background-color: #F9F7E8;">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-xl text-sm shadow-sm">
                    <p class="font-bold mb-1">Mohon periksa kembali inputan Anda:</p>
                    <ul class="list-disc list-inside text-xs opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                
                <div class="p-6 text-center text-white" style="background-color: #D34E4E;">
                    <h3 class="text-xl font-bold uppercase tracking-wider text-white">Formulir Pendaftaran</h3>
                    <p class="text-xs text-red-100 mt-1">Lengkapi data berkas administrasi Anda sesuai dengan ketentuan UAS</p>
                </div>

                <form action="{{ route('mahasiswa.simpan') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                    @csrf
                    
                    <input type="hidden" name="kelas_coding_id" value="{{ $kelas->id }}">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" required placeholder="Masukkan NISN aktif Anda" 
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Mahasiswa</label>
                        <input type="text" name="nama" value="{{ old('nama', auth()->user()->name) }}" required placeholder="Masukkan nama Anda" 
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                        <textarea name="alamat" required placeholder="Masukkan alamat domisili saat ini" rows="3"
                                  class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Contoh: Banjarmasin" 
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required 
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required placeholder="Masukkan nama sekolah asal" 
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required 
                                    class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Jurusan / Program Studi</label>
                            <input type="text" name="jurusan" value="{{ old('jurusan') }}" required placeholder="Contoh: Teknik Informatika" 
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Ayah</label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" required placeholder="Nama lengkap ayah" 
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" required placeholder="Contoh: Wiraswasta / PNS" 
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nama Ibu</label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" required placeholder="Nama lengkap ibu" 
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" required placeholder="Contoh: Ibu Rumah Tangga / Guru" 
                                   class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Penghasilan Total Orang Tua (Rupiah)</label>
                        <input type="number" name="penghasilan_orang_tua" value="{{ old('penghasilan_orang_tua') }}" required placeholder="Contoh: 3000000 (Tulis nominal angka saja)" 
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#D34E4E] focus:border-[#D34E4E] transition duration-150">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Foto Berkas (.jpg, .jpeg, .png)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl bg-gray-50 hover:bg-gray-100 transition duration-150">
                            <div class="space-y-1 text-center w-full flex flex-col items-center justify-center">
                                
                                <div id="preview-container" class="mb-2">
                                    <span id="default-icon" class="inline-block text-2xl text-gray-400 mb-1">📸</span>
                                    <img id="image-preview" src="#" alt="Pratinjau Berkas" class="hidden max-h-40 rounded-lg border border-gray-200 shadow-sm object-contain">
                                </div>

                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label class="relative cursor-pointer bg-white rounded-md font-semibold text-[#D34E4E] hover:text-[#b83f3f] focus-within:outline-none">
                                        <span id="upload-text">Unggah file foto</span>
                                        <input type="file" id="foto-input" name="foto" required class="sr-only" accept="image/*" onchange="previewImage(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-400">Pastikan berkas terlihat jelas</p>
                            </div>
                        </div>
                    </div>

                    <script>
                        function previewImage(input) {
                            const file = input.files[0];
                            const preview = document.getElementById('image-preview');
                            const defaultIcon = document.getElementById('default-icon');
                            const uploadText = document.getElementById('upload-text');

                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.classList.remove('hidden');
                                    if (defaultIcon) defaultIcon.classList.add('hidden');
                                    uploadText.innerText = "Ganti file foto";
                                }
                                reader.readAsDataURL(file);
                            }
                        }
                    </script>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('mahasiswa.dashboard') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs transition duration-150 shadow-sm">
                            Kembali
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-white font-semibold rounded-xl text-xs shadow-md transition duration-150 hover:opacity-95" style="background-color: #D34E4E;">
                            Simpan Pendaftaran
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>