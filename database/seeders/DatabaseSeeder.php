<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Insert default criteria
        DB::table('scholarship_criteria')->insert([
            [
                'nama_kriteria' => 'IPK',
                'bobot' => 30.00,
                'jenis_kriteria' => 'Core Factor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kriteria' => 'Pengalaman Organisasi',
                'bobot' => 30.00,
                'jenis_kriteria' => 'Core Factor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kriteria' => 'Penghasilan Orang Tua',
                'bobot' => 10.00,
                'jenis_kriteria' => 'Secondary Factor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kriteria' => 'Kontribusi Sosial',
                'bobot' => 10.00,
                'jenis_kriteria' => 'Secondary Factor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kriteria' => 'Jumlah Tanggungan',
                'bobot' => 10.00,
                'jenis_kriteria' => 'Secondary Factor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_kriteria' => 'Semester',
                'bobot' => 10.00,
                'jenis_kriteria' => 'Secondary Factor',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert default sub-criteria
        $subCriteria = [
            // IPK (criteria_id: 1)
            [1, 'IPK < 2.5', 'IPK kurang dari 2.5', 1, 1],
            [1, 'IPK >= 2.5 dan < 3', 'IPK antara 2.5 hingga kurang dari 3', 2, 2],
            [1, 'IPK >= 3 dan < 3.5', 'IPK antara 3 hingga kurang dari 3.5', 3, 3],
            [1, 'IPK >= 3.5', 'IPK 3.5 ke atas', 4, 4],
            
            // Pengalaman Organisasi (criteria_id: 2)
            [2, 'Tidak memiliki pengalaman organisasi', 'Tidak pernah terlibat dalam organisasi', 1, 1],
            [2, 'Anggota organisasi tanpa jabatan/hanya partisipasi pasif', 'Hanya sebagai anggota biasa', 2, 2],
            [2, 'Pernah/Sedang menjadi pengurus di 1 organisasi', 'Pengurus di satu organisasi', 3, 3],
            [2, 'Pernah/Sedang menjadi pengurus di >1 organisasi atau menjabat posisi strategis', 'Pengurus di beberapa organisasi atau posisi penting', 4, 4],
            
            // Penghasilan Orang Tua (criteria_id: 3)
            [3, '>= 5,000,000', 'Penghasilan 5 juta ke atas', 1, 1],
            [3, '>= 3,000,000 dan < 5,000,000', 'Penghasilan 3-5 juta', 2, 2],
            [3, '>= 1,500,000 dan < 3,000,000', 'Penghasilan 1.5-3 juta', 3, 3],
            [3, '< 1,500,000', 'Penghasilan kurang dari 1.5 juta', 4, 4],
            
            // Kontribusi Sosial (criteria_id: 4)
            [4, 'Tidak ada kontribusi sosial yang tercatat', 'Tidak pernah berkontribusi', 1, 1],
            [4, 'Pernah terlibat sebagai peserta biasa/volunteer sesekali di 1 kegiatan', 'Volunteer sesekali', 2, 2],
            [4, 'Pernah terlibat sebagai panitia biasa di ≥1 acara kampus/sosial atau volunteer aktif di 1 kegiatan', 'Panitia atau volunteer aktif', 3, 3],
            [4, 'Pernah terlibat sebagai panitia inti/koordinator di ≥1 acara kampus/sosial atau volunteer aktif di >1 kegiatan', 'Panitia inti atau volunteer di banyak kegiatan', 4, 4],
            
            // Jumlah Tanggungan (criteria_id: 5)
            [5, 'Jumlah 1', '1 orang tanggungan', 1, 1],
            [5, 'Jumlah 2', '2 orang tanggungan', 2, 2],
            [5, 'Jumlah 3', '3 orang tanggungan', 3, 3],
            [5, 'Jumlah > 3', 'Lebih dari 3 orang tanggungan', 4, 4],
            
            // Semester (criteria_id: 6)
            [6, 'Semester <= 2', 'Semester 1-2', 0, 1],
            [6, 'Semester 3 - 4', 'Semester 3-4', 1, 2],
            [6, 'Semester 5 - 6', 'Semester 5-6', 2, 3],
            [6, 'Semester 7 - 8', 'Semester 7-8', 3, 4],
            [6, 'Semester > 8', 'Semester lebih dari 8', 4, 5]
        ];

        foreach ($subCriteria as $sub) {
            DB::table('scholarship_sub_criteria')->insert([
                'criteria_id' => $sub[0],
                'nama_sub_kriteria' => $sub[1],
                'deskripsi' => $sub[2],
                'nilai' => $sub[3],
                'sort_order' => $sub[4],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}