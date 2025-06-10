<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Penilaian Beasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'bounce-in': 'bounceIn 0.8s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .success-gradient {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .warning-gradient {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        }

        .danger-gradient {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        }
    </style>
</head>

<body class="gradient-bg min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-white mb-4">
                Hasil Penilaian Beasiswa
            </h1>
            <p class="text-xl text-white/80">
                Profile Matching Method
            </p>
            <div class="w-24 h-1 bg-white/60 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Main Result Card -->
        <div class="glass-effect rounded-2xl p-8 shadow-2xl mb-8 animate-bounce-in">
            <!-- Personal Info -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">{{ $result['nama'] }}</h2>
                <p class="text-xl text-white/80">{{ $result['jurusan'] }}</p>
            </div>

            <!-- Score Display -->
            <div class="text-center mb-8">
                <div class="inline-block p-8 rounded-full 
                    @if($result['total_score'] >= 4.5) success-gradient
                    @elseif($result['total_score'] >= 4.0) warning-gradient  
                        @else danger-gradient
                    @endif
                    shadow-2xl">
                    <div class="text-6xl font-bold text-white mb-2">
                        {{ $result['total_score'] }}
                    </div>
                    <div class="text-lg text-white/90">
                        Total Nilai
                    </div>
                </div>
                <div class="mt-6">
                    <span class="inline-block px-6 py-3 rounded-full text-white font-bold text-xl
                        @if($result['total_score'] >= 4.5) success-gradient
                        @elseif($result['total_score'] >= 4.0) warning-gradient
                            @else danger-gradient
                        @endif
                        shadow-lg">
                        {{ $result['eligibility'] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Detailed Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Profile Comparison -->
            <div class="glass-effect rounded-xl p-6 animate-slide-up">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10z">
                        </path>
                    </svg>
                    Perbandingan Profil
                </h3>
                <div class="space-y-4">
                    @php
                        $criteriaNames = [
                            'ipk' => 'IPK',
                            'pengalaman_organisasi' => 'Pengalaman Organisasi',
                            'penghasilan_orang_tua' => 'Penghasilan Orang Tua',
                            'kontribusi_sosial' => 'Kontribusi Sosial',
                            'jumlah_tanggungan' => 'Jumlah Tanggungan',
                            'semester' => 'Semester'
                        ];
                    @endphp

                    @foreach($criteriaNames as $key => $name)
                        <div class="bg-white/5 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-white font-semibold">{{ $name }}</span>
                                <span class="text-white/80 text-sm">
                                    Gap: {{ $result['gaps'][$key] > 0 ? '+' : '' }}{{ $result['gaps'][$key] }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-white/70">
                                    Anda: {{ $result['user_profile'][$key] }}
                                </span>
                                <span class="text-white/70">
                                    Standar: {{ $result['standard_profile'][$key] }}
                                </span>
                                <span class="text-white font-semibold">
                                    Nilai: {{ $result['gap_values'][$key] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Factor Analysis -->
            <div class="glass-effect rounded-xl p-6 animate-slide-up">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    Analisis Faktor
                </h3>

                <!-- Core Factor -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-lg font-semibold text-white">Core Factor (60%)</h4>
                        <span
                            class="text-xl font-bold text-white">{{ number_format($result['core_average'], 2) }}</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-3 mb-2">
                        <div class="h-3 rounded-full success-gradient"
                            style="width: {{ ($result['core_average'] / 5) * 100 }}%"></div>
                    </div>
                    <p class="text-white/70 text-sm">IPK & Pengalaman Organisasi</p>
                </div>

                <!-- Secondary Factor -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-lg font-semibold text-white">Secondary Factor (40%)</h4>
                        <span
                            class="text-xl font-bold text-white">{{ number_format($result['secondary_average'], 2) }}</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-3 mb-2">
                        <div class="h-3 rounded-full warning-gradient"
                            style="width: {{ ($result['secondary_average'] / 5) * 100 }}%"></div>
                    </div>
                    <p class="text-white/70 text-sm">Penghasilan, Kontribusi Sosial, Tanggungan & Semester</p>
                </div>

                <!-- Final Calculation -->
                <div class="bg-white/10 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-white mb-3">Perhitungan Final</h4>
                    <div class="space-y-2 text-white/80">
                        <div class="flex justify-between">
                            <span>Core ({{ number_format($result['core_average'], 2) }} × 60%)</span>
                            <span>{{ number_format($result['core_average'] * 0.6, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Secondary ({{ number_format($result['secondary_average'], 2) }} × 40%)</span>
                            <span>{{ number_format($result['secondary_average'] * 0.4, 2) }}</span>
                        </div>
                        <hr class="border-white/30">
                        <div class="flex justify-between font-bold text-white text-lg">
                            <span>Total Nilai</span>
                            <span>{{ $result['total_score'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendation -->
        <div class="glass-effect rounded-xl p-6 mb-8 animate-fade-in">
            <h3 class="text-2xl font-bold text-white mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                    </path>
                </svg>
                Rekomendasi
            </h3>

            @if($result['total_score'] >= 4.5)
                <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4">
                    <p class="text-white">
                        <strong>Selamat!</strong> Profil Anda sangat sesuai dengan kriteria beasiswa.
                        Anda memiliki peluang yang sangat tinggi untuk menerima beasiswa ini.
                    </p>
                </div>
            @elseif($result['total_score'] >= 4.0)
                <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-lg p-4">
                    <p class="text-white">
                        Profil Anda cukup baik untuk kriteria beasiswa. Pertimbangkan untuk meningkatkan
                        aspek yang masih kurang untuk memperbesar peluang Anda.
                    </p>
                </div>
            @else
                <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4">
                    <p class="text-white">
                        Profil Anda perlu ditingkatkan untuk memenuhi kriteria beasiswa.
                        Fokuskan pada peningkatan IPK, pengalaman organisasi, dan kontribusi sosial.
                    </p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-slide-up">
            <a href="{{ route('scholarship.form') }}"
                class="px-8 py-4 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg transition-all duration-300 transform hover:scale-105 text-center backdrop-blur-sm">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Hitung Ulang
                </span>
            </a>

            <button onclick="window.print()"
                class="px-8 py-4 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg transition-all duration-300 transform hover:scale-105 backdrop-blur-sm">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak Hasil
                </span>
            </button>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 text-center animate-fade-in">
            <p class="text-white/70 text-sm">
                Hasil perhitungan menggunakan metode Profile Matching dengan bobot Core Factor 60% dan Secondary Factor
                40%
            </p>
        </div>
    </div>

    <script>
        // Add staggered animation delays
        document.addEventListener('DOMContentLoaded', function () {
            const elements = document.querySelectorAll('.animate-slide-up');
            elements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Print styles
        const printStyles = `
            @media print {
                body { 
                    background: white !important; 
                    color: black !important;
                }
                .glass-effect {
                    background: white !important;
                    border: 1px solid #ccc !important;
                }
                .gradient-bg { background: white !important; }
                .success-gradient, .warning-gradient, .danger-gradient {
                    background: #f0f0f0 !important;
                    color: black !important;
                }
            }
        `;

        const style = document.createElement('style');
        style.textContent = printStyles;
        document.head.appendChild(style);
    </script>
</body>

</html>