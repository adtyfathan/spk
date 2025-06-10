<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penilaian Beasiswa</title>
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
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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
    </style>
</head>

<body class="gradient-bg min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-white mb-4">
                Riwayat Penilaian Beasiswa
            </h1>
            <p class="text-xl text-white/80">
                Daftar semua aplikasi yang telah dinilai
            </p>
            <div class="w-24 h-1 bg-white/60 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Navigation -->
        <div class="mb-6 animate-slide-up">
            <a href="{{ route('scholarship.form') }}"
                class="inline-flex items-center px-6 py-3 glass-effect rounded-lg text-white font-semibold hover:bg-white/20 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Penilaian Baru
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $stats = [
                    ['label' => 'Total Aplikasi', 'value' => $applications->total(), 'color' => 'bg-blue-500'],
                    ['label' => 'Sangat Layak', 'value' => $applications->where('eligibility', 'Sangat Layak')->count(), 'color' => 'bg-green-500'],
                    ['label' => 'Layak', 'value' => $applications->where('eligibility', 'Layak')->count(), 'color' => 'bg-yellow-500'],
                    ['label' => 'Kurang Layak', 'value' => $applications->where('eligibility', 'Kurang Layak')->count(), 'color' => 'bg-red-500']
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="glass-effect rounded-xl p-6 text-center animate-slide-up">
                    <div class="w-12 h-12 {{ $stat['color'] }} rounded-full mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-white mb-2">{{ $stat['value'] }}</div>
                    <div class="text-white/70">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Applications Table -->
        <div class="glass-effect rounded-2xl overflow-hidden shadow-2xl animate-slide-up">
            <div class="p-6 border-b border-white/10">
                <h2 class="text-2xl font-bold text-white">Daftar Aplikasi</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-4 text-left text-white font-semibold">Nama</th>
                            <th class="px-6 py-4 text-left text-white font-semibold">Jurusan</th>
                            <th class="px-6 py-4 text-left text-white font-semibold">IPK</th>
                            <th class="px-6 py-4 text-left text-white font-semibold">Total Nilai</th>
                            <th class="px-6 py-4 text-left text-white font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-white font-semibold">Tanggal</th>
                            <th class="px-6 py-4 text-left text-white font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($applications as $application)
                            <tr class="hover:bg-white/5 transition-colors duration-200">
                                <td class="px-6 py-4 text-white font-medium">{{ $application->nama }}</td>
                                <td class="px-6 py-4 text-white/80">{{ $application->jurusan }}</td>
                                <td class="px-6 py-4 text-white/80">{{ $application->ipk }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-2xl font-bold text-white">{{ $application->total_score }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if($application->eligibility == 'Sangat Layak') bg-green-500/20 text-green-300
                                            @elseif($application->eligibility == 'Layak') bg-blue-500/20 text-blue-300
                                            @elseif($application->eligibility == 'Cukup Layak') bg-yellow-500/20 text-yellow-300
                                                @else bg-red-500/20 text-red-300
                                            @endif">
                                        {{ $application->eligibility }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-white/70">
                                    {{ $application->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="showDetails({{ $application->id }})"
                                        class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all duration-300 text-sm">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-white/70">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 mb-4 text-white/30" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-xl mb-2">Belum ada data aplikasi</p>
                                        <p class="text-sm">Mulai dengan membuat penilaian beasiswa pertama</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($applications->hasPages())
                <div class="p-6 border-t border-white/10">
                    <div class="flex justify-center">
                        {{ $applications->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="glass-effect rounded-2xl p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-white">Detail Penilaian</h3>
                    <button onclick="closeModal()" class="text-white/70 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetails(applicationId) {
            // In a real application, you would fetch the details via AJAX
            document.getElementById('detailModal').classList.remove('hidden');

            // For demo purposes, showing a placeholder
            document.getElementById('modalContent').innerHTML = `
                <div class="text-white">
                    <p class="mb-4">Loading application details for ID: ${applicationId}</p>
                    <p class="text-white/70">This would typically load the full calculation details, gap analysis, and scoring breakdown.</p>
                </div>
            `;
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>

</html>