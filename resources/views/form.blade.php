<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penilaian Beasiswa - Multiple Applicants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'bounce-in': 'bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
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
        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .form-card {
            transition: all 0.3s ease;
        }
        .form-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .remove-btn {
            transition: all 0.2s ease;
        }
        .remove-btn:hover {
            transform: rotate(90deg);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold text-white mb-4">
                Sistem Penilaian Beasiswa
            </h1>
            <div class="w-24 h-1 bg-white/60 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Control Panel -->
        <div class="glass-effect rounded-2xl p-6 mb-6 animate-fade-in">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
                    <span class="text-white font-semibold">Total Applicants: <span id="applicantCount">1</span></span>
                </div>
                <div class="flex gap-3">
                    <button id="addApplicant" 
                            class="bg-green-500/20 hover:bg-green-500/30 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 focus:ring-2 focus:ring-green-300/30 backdrop-blur-sm border border-green-400/30">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Applicant
                        </span>
                    </button>
                    <button id="removeAll" 
                            class="bg-red-500/20 hover:bg-red-500/30 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 focus:ring-2 focus:ring-red-300/30 backdrop-blur-sm border border-red-400/30">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Clear All
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form action="{{ route('scholarship.calculate') }}" method="POST" id="multipleForm">
            @csrf
            @method('POST')
            <div id="applicantsContainer" class="space-y-6">
                <!-- First applicant card will be inserted here by JavaScript -->
            </div>

            <!-- Submit Button -->
            <div class="pt-8 text-center">
                <button type="submit" 
                        class="bg-white/20 hover:bg-white/30 text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 focus:ring-4 focus:ring-white/30 backdrop-blur-sm border border-white/20 shadow-2xl">
                    <span class="flex items-center justify-center">
                        <svg clasFs="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Calculate All Scholarship Scores
                    </span>
                </button>
            </div>
        </form>
    </div>

    <script>
        let applicantCount = 0;

        // Template for applicant form
        function createApplicantCard(index) {
            return `
                <div class="glass-effect rounded-2xl p-6 shadow-2xl form-card animate-fade-in" data-applicant="${index}">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Applicant #${index + 1}</h3>
                        ${index > 0 ? `
                        <button type="button" class="remove-applicant remove-btn bg-red-500/20 hover:bg-red-500/30 text-white p-2 rounded-full transition-all duration-200" data-index="${index}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        ` : ''}
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Personal Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-white font-semibold text-sm">
                                    Nama Lengkap
                                </label>
                                <input type="text" name="applicants[${index}][nama]" 
                                       class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300"
                                       placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-white font-semibold text-sm">
                                    Jurusan
                                </label>
                                <input type="text" name="applicants[${index}][jurusan]"
                                       class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300"
                                       placeholder="Masukkan jurusan" required>
                            </div>
                        </div>

                        <!-- IPK -->
                        <div class="space-y-2">
                            <label class="block text-white font-semibold text-sm">
                                IPK (Indeks Prestasi Kumulatif)
                            </label>
                            <input type="number" name="applicants[${index}][ipk]" step="0.01" min="0" max="4"
                                   class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300"
                                   placeholder="Contoh: 3.50" required>
                        </div>

                        <!-- Pengalaman Organisasi -->
                        <div class="space-y-2">
                            <label class="block text-white font-semibold text-sm">
                                Pengalaman Organisasi
                            </label>
                            <select name="applicants[${index}][pengalaman_organisasi]" 
                                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                                <option value="" class="text-gray-800">Pilih pengalaman organisasi</option>
                                <option value="1" class="text-gray-800">Tidak memiliki pengalaman organisasi</option>
                                <option value="2" class="text-gray-800">Anggota organisasi tanpa jabatan/hanya partisipasi pasif</option>
                                <option value="3" class="text-gray-800">Pernah/Sedang menjadi pengurus di 1 organisasi</option>
                                <option value="4" class="text-gray-800">Pernah/Sedang menjadi pengurus di >1 organisasi atau menjabat posisi strategis</option>
                            </select>
                        </div>

                        <!-- Penghasilan Orang Tua -->
                        <div class="space-y-2">
                            <label class="block text-white font-semibold text-sm">
                                Penghasilan Orang Tua per Bulan
                            </label>
                            <select name="applicants[${index}][penghasilan_orang_tua]" 
                                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                                <option value="" class="text-gray-800">Pilih range penghasilan</option>
                                <option value="1" class="text-gray-800">>= Rp 5,000,000</option>
                                <option value="2" class="text-gray-800">Rp 3,000,000 - Rp 4,999,999</option>
                                <option value="3" class="text-gray-800">Rp 1,500,000 - Rp 2,999,999</option>
                                <option value="4" class="text-gray-800">< Rp 1,500,000</option>
                            </select>
                        </div>

                        <!-- Kontribusi Sosial -->
                        <div class="space-y-2">
                            <label class="block text-white font-semibold text-sm">
                                Kontribusi Sosial
                            </label>
                            <select name="applicants[${index}][kontribusi_sosial]" 
                                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                                <option value="" class="text-gray-800">Pilih tingkat kontribusi sosial</option>
                                <option value="1" class="text-gray-800">Tidak ada kontribusi sosial yang tercatat</option>
                                <option value="2" class="text-gray-800">Pernah terlibat sebagai peserta biasa/volunteer sesekali di 1 kegiatan</option>
                                <option value="3" class="text-gray-800">Pernah terlibat sebagai panitia biasa di ≥1 acara kampus/sosial atau volunteer aktif di 1 kegiatan</option>
                                <option value="4" class="text-gray-800">Pernah terlibat sebagai panitia inti/koordinator di ≥1 acara kampus/sosial atau volunteer aktif di >1 kegiatan</option>
                            </select>
                        </div>

                        <!-- Jumlah Tanggungan -->
                        <div class="space-y-2">
                            <label class="block text-white font-semibold text-sm">
                                Jumlah Tanggungan Keluarga
                            </label>
                            <select name="applicants[${index}][jumlah_tanggungan]" 
                                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                                <option value="" class="text-gray-800">Pilih jumlah tanggungan</option>
                                <option value="1" class="text-gray-800">1 orang</option>
                                <option value="2" class="text-gray-800">2 orang</option>
                                <option value="3" class="text-gray-800">3 orang</option>
                                <option value="4" class="text-gray-800">> 3 orang</option>
                            </select>
                        </div>

                        <!-- Semester -->
                        <div class="space-y-2">
                            <label class="block text-white font-semibold text-sm">
                                Semester Saat Ini
                            </label>
                            <select name="applicants[${index}][semester]" 
                                    class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-white/30 focus:border-transparent transition-all duration-300" required>
                                <option value="" class="text-gray-800">Pilih semester</option>
                                <option value="0" class="text-gray-800">Semester 1-2</option>
                                <option value="1" class="text-gray-800">Semester 3-4</option>
                                <option value="2" class="text-gray-800">Semester 5-6</option>
                                <option value="3" class="text-gray-800">Semester 7-8</option>
                                <option value="4" class="text-gray-800">Semester > 8</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add first applicant on page load
        function init() {
            addApplicant();
        }

        // Add applicant function
        function addApplicant() {
            const container = document.getElementById('applicantsContainer');
            const newCard = document.createElement('div');
            newCard.innerHTML = createApplicantCard(applicantCount);
            container.appendChild(newCard.firstElementChild);
            
            applicantCount++;
            updateApplicantCount();
            
            // Add event listeners to new form elements
            addFormEventListeners(container.lastElementChild);
        }

        // Remove applicant function
        function removeApplicant(index) {
            const applicantCard = document.querySelector(`[data-applicant="${index}"]`);
            if (applicantCard) {
                applicantCard.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    applicantCard.remove();
                    reindexApplicants();
                }, 300);
            }
        }

        // Reindex applicants after removal
        function reindexApplicants() {
            const applicantCards = document.querySelectorAll('[data-applicant]');
            applicantCount = 0;
            
            applicantCards.forEach((card, index) => {
                card.setAttribute('data-applicant', index);
                card.querySelector('h3').textContent = `Applicant #${index + 1}`;
                
                // Update all input names
                const inputs = card.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const name = input.name;
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.name = newName;
                    }
                });
                
                // Update remove button
                const removeBtn = card.querySelector('.remove-applicant');
                if (removeBtn) {
                    removeBtn.setAttribute('data-index', index);
                    if (index === 0) {
                        removeBtn.style.display = 'none';
                    } else {
                        removeBtn.style.display = 'block';
                    }
                }
                
                applicantCount++;
            });
            
            updateApplicantCount();
        }

        // Update applicant count display
        function updateApplicantCount() {
            document.getElementById('applicantCount').textContent = applicantCount;
        }

        // Add form event listeners
        function addFormEventListeners(card) {
            const inputs = card.querySelectorAll('input, select');
            inputs.forEach(element => {
                element.addEventListener('focus', function() {
                    this.classList.add('transform', 'scale-105');
                });
                
                element.addEventListener('blur', function() {
                    this.classList.remove('transform', 'scale-105');
                });
            });
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            init();
            
            document.getElementById('addApplicant').addEventListener('click', addApplicant);
            
            document.getElementById('removeAll').addEventListener('click', function() {
                if (confirm('Are you sure you want to remove all applicants?')) {
                    const container = document.getElementById('applicantsContainer');
                    container.innerHTML = '';
                    applicantCount = 0;
                    addApplicant(); // Add one applicant back
                }
            });
            
            // Event delegation for remove buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-applicant')) {
                    const index = parseInt(e.target.closest('.remove-applicant').getAttribute('data-index'));
                    if (applicantCount > 1) {
                        removeApplicant(index);
                    }
                }
            });
        });

        // Add fadeOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-20px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>