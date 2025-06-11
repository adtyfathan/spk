<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Penilaian Beasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .warning {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        }

        .danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-white mb-2">Hasil Penilaian Beasiswa</h1>
            <p class="text-lg text-white/80">Profile Matching Method - {{ count($results) }} Kandidat</p>
            <div class="w-24 h-1 bg-white/60 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Score Chart -->
        <div class="glass-effect rounded-xl p-6 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6">Diagram Perbandingan Nilai</h3>
            <div class="bg-white/10 rounded-lg p-4">
                <canvas id="scoreChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Results Table -->
        <div class="glass-effect rounded-xl p-6 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6">Detail Hasil Penilaian</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-white">
                    <thead>
                        <tr class="border-b border-white/20">
                            <th class="text-left py-3 px-2">No</th>
                            <th class="text-left py-3 px-2">Nama</th>
                            <th class="text-left py-3 px-2">Jurusan</th>
                            <th class="text-center py-3 px-2">Core Factor</th>
                            <th class="text-center py-3 px-2">Secondary Factor</th>
                            <th class="text-center py-3 px-2">Total Nilai</th>
                            {{-- <th class="text-center py-3 px-2">Status</th> --}}
                            <th class="text-center py-3 px-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $index => $result)
                            <tr class="border-b border-white/10 hover:bg-white/5">
                                <td class="py-3 px-2">{{ $index + 1}}</td>
                                <td class="py-3 px-2 font-semibold">{{ $result['nama'] }}</td>
                                <td class="py-3 px-2">{{ $result['jurusan'] }}</td>
                                <td class="py-3 px-2 text-center">{{ number_format($result['core_average'], 2) }}</td>
                                <td class="py-3 px-2 text-center">{{ number_format($result['secondary_average'], 2) }}</td>
                                <td class="py-3 px-2 text-center">
                                    <span class="font-bold text-lg">{{ $result['total_score'] }}</span>
                                </td>
                                {{-- <td class="py-3 px-2 text-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        @if($result['eligibility'] == 'LAYAK') bg-green-500/20 text-green-300
                                        @elseif($result['eligibility'] == 'PERTIMBANGAN') bg-yellow-500/20 text-yellow-300
                                            @else bg-red-500/20 text-red-300
                                        @endif">
                                        {{ $result['eligibility'] }}
                                    </span>
                                </td> --}}
                                <td class="py-3 px-2 text-center">
                                    <button onclick="showDetail({{ $index }})"
                                        class="px-3 py-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 rounded-lg transition-colors">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Modal -->
        <div id="detailModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
            <div class="glass-effect rounded-xl p-6 max-w-5xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-white">Detail Penilaian</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white text-2xl">&times;</button>
                </div>
                <div id="modalContent"></div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('scholarship.form') }}"
                class="px-8 py-4 bg-white/20 hover:bg-white/30 text-white font-bold rounded-lg transition-all duration-300 text-center">
                Input Data Baru
            </a>
        </div>
    </div>

    <script>
        // Results data for JavaScript
        const results = @json($results);

        // Create Chart
        const ctx = document.getElementById('scoreChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: results.map(r => r.nama.length > 15 ? r.nama.substring(0, 15) + '...' : r.nama),
                datasets: [{
                    label: 'Total Nilai',
                    data: results.map(r => r.total_score),
                    backgroundColor: results.map(r => {
                        if (r.total_score >= 4.5) return 'rgba(72, 187, 120, 0.8)';
                        if (r.total_score >= 4.0) return 'rgba(237, 137, 54, 0.8)';
                        return 'rgba(245, 101, 101, 0.8)';
                    }),
                    borderColor: results.map(r => {
                        if (r.total_score >= 4.5) return 'rgba(72, 187, 120, 1)';
                        if (r.total_score >= 4.0) return 'rgba(237, 137, 54, 1)';
                        return 'rgba(245, 101, 101, 1)';
                    }),
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: { color: 'rgba(255, 255, 255, 0.8)' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            maxRotation: 45
                        },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    }
                }
            }
        });

        // Detail Modal Functions
        function showDetail(index) {
            const result = results[index];
            const criteriaNames = {
                'ipk': 'IPK',
                'pengalaman_organisasi': 'Pengalaman Organisasi',
                'penghasilan_orang_tua': 'Penghasilan Orang Tua',
                'kontribusi_sosial': 'Kontribusi Sosial',
                'jumlah_tanggungan': 'Jumlah Tanggungan',
                'semester': 'Semester'
            };

            let content = `
                <div class="text-center mb-6">
                    <h4 class="text-2xl font-bold text-white mb-2">${result.nama}</h4>
                    <p class="text-white/80">${result.jurusan}</p>
                    <div class="mt-4 inline-block p-6 rounded-full ${getScoreClass(result.total_score)}">
                        <div class="text-4xl font-bold text-white">${result.total_score}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-4 gap-6">  
                    <div class="bg-white/5 rounded-lg p-4">
                        <h5 class="text-lg font-semibold text-white mb-4">Profil Mahasiswa</h5>
                        <div class="space-y-3">
            `;

            Object.keys(criteriaNames).forEach(key => {
                if (result.gaps && result.gaps[key] !== undefined) {
                    content += `
                        <div class="flex justify-between items-center">
                            <span class="text-white/80 text-sm">${criteriaNames[key]}</span>
                            <span class="text-white font-semibold">${result.user_profile[key]}</span>
                        </div>
                    `;
                }
            });

            content += `
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-lg p-4">
                        <h5 class="text-lg font-semibold text-white mb-4">Profil Standar</h5>
                        <div class="space-y-3">
            `;

            Object.keys(criteriaNames).forEach(key => {
                if (result.gaps && result.gaps[key] !== undefined) {
                    content += `
                        <div class="flex justify-between items-center">
                            <span class="text-white/80 text-sm">${criteriaNames[key]}</span>
                            <span class="text-white font-semibold">${result.standard_profile[key]}</span>
                        </div>
                    `;
                }
            });

            content += `
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-lg p-4">
                        <h5 class="text-lg font-semibold text-white mb-4">Gap</h5>
                        <div class="space-y-3">
            `;

            Object.keys(criteriaNames).forEach(key => {
                if (result.gaps && result.gaps[key] !== undefined) {
                    content += `
                        <div class="flex justify-between items-center">
                            <span class="text-white/80 text-sm">${criteriaNames[key]}</span>
                            <span class="text-white font-semibold">${result.gaps[key]}</span>
                        </div>
                    `;
                }
            });

            content += `
                        </div>
                    </div>

                    <div class="bg-white/5 rounded-lg p-4">
                        <h5 class="text-lg font-semibold text-white mb-4">Nilai Gap</h5>
                        <div class="space-y-3">
            `;

            Object.keys(criteriaNames).forEach(key => {
                if (result.gaps && result.gaps[key] !== undefined) {
                    content += `
                        <div class="flex justify-between items-center">
                            <span class="text-white/80 text-sm">${criteriaNames[key]}</span>
                            <span class="text-white font-semibold">${result.gap_values[key]}</span>
                        </div>
                    `;
                }
            });

            content += `
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 bg-white/5 rounded-lg p-4">
                    <h5 class="text-lg font-semibold text-white mb-4">Factor Analysis</h5>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-white/80">Core Factor (60%)</span>
                                <span class="text-white font-semibold">${result.core_average.toFixed(2)}</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="h-2 rounded-full bg-green-500" style="width: ${(result.core_average / 5) * 100}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-white/80">Secondary Factor (40%)</span>
                                <span class="text-white font-semibold">${result.secondary_average.toFixed(2)}</span>
                            </div>
                            <div class="w-full bg-white/20 rounded-full h-2">
                                <div class="h-2 rounded-full bg-yellow-500" style="width: ${(result.secondary_average / 5) * 100}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        function getScoreClass(score) {
            if (score >= 4.5) return 'success';
            if (score >= 4.0) return 'warning';
            return 'danger';
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });

        // Print styles
        const printStyles = `
            @media print {
                body { background: white !important; color: black !important; }
                .glass-effect { background: white !important; border: 1px solid #ccc !important; }
                .gradient-bg { background: white !important; }
                #detailModal { display: none !important; }
                canvas { display: none !important; }
            }
        `;
        const style = document.createElement('style');
        style.textContent = printStyles;
        document.head.appendChild(style);
    </script>
</body>

</html>