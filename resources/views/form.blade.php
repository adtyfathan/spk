<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penilaian Beasiswa - Profile Matching</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-white mb-4">
                Sistem Penilaian Beasiswa
            </h1>
            <p class="text-xl text-white/80">
                Menggunakan Metode Profile Matching
            </p>
            <div class="w-24 h-1 bg-white/60 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Main Form -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl animate-slide-up">
            <form action="{{ route('scholarship.calculate') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-white font-semibold text-sm">
                            Nama Lengkap
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" 
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300"
                               placeholder="Masukkan nama lengkap" required>
                        @error('nama')
                            <p class="text-red-300 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-white font-semibold text-sm">
                            Jurusan
                        </label>
                        <input type="text" name="jurusan" value="{{ old('jurusan') }}"
                               class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300"
                               placeholder="Masukkan jurusan" required>
                        @error('jurusan')
                            <p class="text-red-300 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- IPK -->
                <div class="space-y-2">
                    <label class="block text-white font-semibold text-sm">
                        IPK (Indeks Prestasi Kumulatif)
                    </label>
                    <input type="number" name="ipk" value="{{ old('ipk') }}" step="0.01" min="0" max="4"
                           class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300"
                           placeholder="Contoh: 3.50" required>
                    @error('ipk')
                        <p class="text-red-300 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pengalaman Organisasi -->
                <div class="space-y-2">
                    <label class="block text-white font-semibold text-sm">
                        Pengalaman Organisasi
                    </label>
                    <select name="pengalaman_organisasi" 
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                        <option value="" class="text-gray-800">Pilih pengalaman organisasi</option>
                        <option value="1" class="text-gray-800" {{ old('pengalaman_organisasi') == '1' ? 'selected' : '' }}>
                            Tidak memiliki pengalaman organisasi
                        </option>
                        <option value="2" class="text-gray-800" {{ old('pengalaman_organisasi') == '2' ? 'selected' : '' }}>
                            Anggota organisasi tanpa jabatan/hanya partisipasi pasif
                        </option>
                        <option value="3" class="text-gray-800" {{ old('pengalaman_organisasi') == '3' ? 'selected' : '' }}>
                            Pernah/Sedang menjadi pengurus di 1 organisasi
                        </option>
                        <option value="4" class="text-gray-800" {{ old('pengalaman_organisasi') == '4' ? 'selected' : '' }}>
                            Pernah/Sedang menjadi pengurus di >1 organisasi atau menjabat posisi strategis
                        </option>
                    </select>
                    @error('pengalaman_organisasi')
                        <p class="text-red-300 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penghasilan Orang Tua -->
                <div class="space-y-2">
                    <label class="block text-white font-semibold text-sm">
                        Penghasilan Orang Tua per Bulan
                    </label>
                    <select name="penghasilan_orang_tua" 
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                        <option value="" class="text-gray-800">Pilih range penghasilan</option>
                        <option value="1" class="text-gray-800" {{ old('penghasilan_orang_tua') == '1' ? 'selected' : '' }}>
                            >= Rp 5,000,000
                        </option>
                        <option value="2" class="text-gray-800" {{ old('penghasilan_orang_tua') == '2' ? 'selected' : '' }}>
                            Rp 3,000,000 - Rp 4,999,999
                        </option>
                        <option value="3" class="text-gray-800" {{ old('penghasilan_orang_tua') == '3' ? 'selected' : '' }}>
                            Rp 1,500,000 - Rp 2,999,999
                        </option>
                        <option value="4" class="text-gray-800" {{ old('penghasilan_orang_tua') == '4' ? 'selected' : '' }}>
                            < Rp 1,500,000
                        </option>
                    </select>
                    @error('penghasilan_orang_tua')
                        <p class="text-red-300 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kontribusi Sosial -->
                <div class="space-y-2">
                    <label class="block text-white font-semibold text-sm">
                        Kontribusi Sosial
                    </label>
                    <select name="kontribusi_sosial" 
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                        <option value="" class="text-gray-800">Pilih tingkat kontribusi sosial</option>
                        <option value="1" class="text-gray-800" {{ old('kontribusi_sosial') == '1' ? 'selected' : '' }}>
                            Tidak ada kontribusi sosial yang tercatat
                        </option>
                        <option value="2" class="text-gray-800" {{ old('kontribusi_sosial') == '2' ? 'selected' : '' }}>
                            Pernah terlibat sebagai peserta biasa/volunteer sesekali di 1 kegiatan
                        </option>
                        <option value="3" class="text-gray-800" {{ old('kontribusi_sosial') == '3' ? 'selected' : '' }}>
                            Pernah terlibat sebagai panitia biasa di ≥1 acara kampus/sosial atau volunteer aktif di 1 kegiatan
                        </option>
                        <option value="4" class="text-gray-800" {{ old('kontribusi_sosial') == '4' ? 'selected' : '' }}>
                            Pernah terlibat sebagai panitia inti/koordinator di ≥1 acara kampus/sosial atau volunteer aktif di >1 kegiatan
                        </option>
                    </select>
                    @error('kontribusi_sosial')
                        <p class="text-red-300 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah Tanggungan -->
                <div class="space-y-2">
                    <label class="block text-white font-semibold text-sm">
                        Jumlah Tanggungan Keluarga
                    </label>
                    <select name="jumlah_tanggungan" 
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                        <option value="" class="text-gray-800">Pilih jumlah tanggungan</option>
                        <option value="1" class="text-gray-800" {{ old('jumlah_tanggungan') == '1' ? 'selected' : '' }}>
                            1 orang
                        </option>
                        <option value="2" class="text-gray-800" {{ old('jumlah_tanggungan') == '2' ? 'selected' : '' }}>
                            2 orang
                        </option>
                        <option value="3" class="text-gray-800" {{ old('jumlah_tanggungan') == '3' ? 'selected' : '' }}>
                            3 orang
                        </option>
                        <option value="4" class="text-gray-800" {{ old('jumlah_tanggungan') == '4' ? 'selected' : '' }}>
                            > 3 orang
                        </option>
                    </select>
                    @error('jumlah_tanggungan')
                        <p class="text-red-300 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Semester -->
                <div class="space-y-2">
                    <label class="block text-white font-semibold text-sm">
                        Semester Saat Ini
                    </label>
                    <select name="semester" 
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                        <option value="" class="text-gray-800">Pilih semester</option>
                        <option value="0" class="text-gray-800" {{ old('semester') == '0' ? 'selected' : '' }}>
                            Semester 1-2
                        </option>
                        <option value="1" class="text-gray-800" {{ old('semester') == '1' ? 'selected' : '' }}>
                            Semester 3-4
                        </option>
                        <option value="2" class="text-gray-800" {{ old('semester') == '2' ? 'selected' : '' }}>
                            Semester 5-6
                        </option>
                        <option value="3" class="text-gray-800" {{ old('semester') == '3' ? 'selected' : '' }}>
                            Semester 7-8
                        </option>
                        <option value="4" class="text-gray-800" {{ old('semester') == '4' ? 'selected' : '' }}>
                            Semester > 8
                        </option>
                    </select>
                    @error('semester')
                        <p class="text-red-300 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" 
                            class="w-full bg-white/20 hover:bg-white/30 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 focus:ring-4 focus:ring-white/30 backdrop-blur-sm">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Hitung Nilai Beasiswa
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="mt-8 text-center animate-fade-in">
            <p class="text-white/70 text-sm">
                Sistem ini menggunakan metode Profile Matching untuk menilai kelayakan penerima beasiswa berdasarkan kriteria yang telah ditentukan.
            </p>
        </div>
    </div>

    <script>
        // Add smooth animations on form interaction
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('focus', function() {
                this.classList.add('transform', 'scale-105');
            });
            
            element.addEventListener('blur', function() {
                this.classList.remove('transform', 'scale-105');
            });
        });
    </script>
</body>
</html>