<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ScholarshipApplication;
use App\Models\ScholarshipCriteria;
use App\Models\ScholarshipSubCriteria;

class ScholarshipController extends Controller
{
    // Standard profile values based on your Excel
    private $standardProfile = [
        'ipk' => 4,
        'pengalaman_organisasi' => 4,
        'penghasilan_orang_tua' => 2,
        'kontribusi_sosial' => 3,
        'jumlah_tanggungan' => 3,
        'semester' => 1
    ];

    // Gap weight mapping
    private $gapWeights = [
        0 => 5,
        1 => 4.5,
        -1 => 4,
        2 => 3.5,
        -2 => 3,
        3 => 2.5,
        -3 => 2,
        4 => 1.5,
        -4 => 1
    ];

    // Core and Secondary factor percentages
    private $coreFactorPercent = 0.6;
    private $secondaryFactorPercent = 0.4;

    public function index()
    {
        return view('form');
    }

    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'ipk' => 'required|numeric|min:0|max:4',
            'pengalaman_organisasi' => 'required|integer|min:1|max:4',
            'penghasilan_orang_tua' => 'required|integer|min:1|max:4',
            'kontribusi_sosial' => 'required|integer|min:1|max:4',
            'jumlah_tanggungan' => 'required|integer|min:1|max:4',
            'semester' => 'required|integer|min:0|max:4',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $result = $this->processProfileMatching($request->all());

        // Save to database
        $this->saveApplication($request->all(), $result);

        return view('result', compact('result'));
    }

    public function history()
    {
        $applications = ScholarshipApplication::latest()->paginate(10);
        return view('scholarship.history', compact('applications'));
    }

    public function manageCriteria()
    {
        $criteria = ScholarshipCriteria::with('subCriteria')->get();
        return view('scholarship.admin.criteria', compact('criteria'));
    }

    private function saveApplication($requestData, $result)
    {
        ScholarshipApplication::create([
            'nama' => $requestData['nama'],
            'jurusan' => $requestData['jurusan'],
            'ipk' => $requestData['ipk'],
            'pengalaman_organisasi' => $requestData['pengalaman_organisasi'],
            'penghasilan_orang_tua' => $requestData['penghasilan_orang_tua'],
            'kontribusi_sosial' => $requestData['kontribusi_sosial'],
            'jumlah_tanggungan' => $requestData['jumlah_tanggungan'],
            'semester' => $requestData['semester'],
            'total_score' => $result['total_score'],
            'eligibility' => $result['eligibility'],
            'calculation_details' => $result
        ]);
    }

    private function processProfileMatching($data)
    {
        // Convert IPK to profile value
        $ipkProfile = $this->convertIPKToProfile($data['ipk']);
        
        // User profile values
        $userProfile = [
            'ipk' => $ipkProfile,
            'pengalaman_organisasi' => $data['pengalaman_organisasi'],
            'penghasilan_orang_tua' => $data['penghasilan_orang_tua'],
            'kontribusi_sosial' => $data['kontribusi_sosial'],
            'jumlah_tanggungan' => $data['jumlah_tanggungan'],
            'semester' => $data['semester']
        ];

        // Calculate gaps
        $gaps = [];
        $gapValues = [];
        
        foreach ($userProfile as $key => $value) {
            $gap = $value - $this->standardProfile[$key];
            $gaps[$key] = $gap;
            $gapValues[$key] = $this->gapWeights[$gap] ?? 1; // Default to 1 if gap not found
        }

        // Separate core and secondary factors
        $coreFactors = ['ipk', 'pengalaman_organisasi'];
        $secondaryFactors = ['penghasilan_orang_tua', 'kontribusi_sosial', 'jumlah_tanggungan', 'semester'];

        // Calculate core factor average
        $coreSum = 0;
        foreach ($coreFactors as $factor) {
            $coreSum += $gapValues[$factor];
        }
        $coreAverage = $coreSum / count($coreFactors);

        // Calculate secondary factor average
        $secondarySum = 0;
        foreach ($secondaryFactors as $factor) {
            $secondarySum += $gapValues[$factor];
        }
        $secondaryAverage = $secondarySum / count($secondaryFactors);

        // Calculate total score (this is your 'total nilai')
        $totalScore = ($coreAverage * $this->coreFactorPercent) + 
                     ($secondaryAverage * $this->secondaryFactorPercent);

        return [
            'nama' => $data['nama'],
            'jurusan' => $data['jurusan'],
            'user_profile' => $userProfile,
            'standard_profile' => $this->standardProfile,
            'gaps' => $gaps,
            'gap_values' => $gapValues,
            'core_average' => round($coreAverage, 2),
            'secondary_average' => round($secondaryAverage, 2),
            'total_score' => round($totalScore, 2), // This is your main output
            'eligibility' => $this->determineEligibility($totalScore)
        ];
    }

    private function convertIPKToProfile($ipk)
    {
        if ($ipk < 2.5) return 1;
        if ($ipk >= 2.5 && $ipk < 3) return 2;
        if ($ipk >= 3 && $ipk < 3.5) return 3;
        if ($ipk >= 3.5) return 4;
        return 1;
    }

    private function determineEligibility($score)
    {
        if ($score >= 4.5) return 'Sangat Layak';
        if ($score >= 4.0) return 'Layak';
        if ($score >= 3.5) return 'Cukup Layak';
        return 'Kurang Layak';
    }

    public function getSubCriteriaOptions()
    {
        return [
            'pengalaman_organisasi' => [
                1 => 'Tidak memiliki pengalaman organisasi',
                2 => 'Anggota organisasi tanpa jabatan/hanya partisipasi pasif',
                3 => 'Pernah/Sedang menjadi pengurus di 1 organisasi',
                4 => 'Pernah/Sedang menjadi pengurus di >1 organisasi atau menjabat posisi strategis'
            ],
            'penghasilan_orang_tua' => [
                1 => '>= 5,000,000',
                2 => '>= 3,000,000 dan < 5,000,000',
                3 => '>= 1,500,000 dan < 3,000,000',
                4 => '< 1,500,000'
            ],
            'kontribusi_sosial' => [
                1 => 'Tidak ada kontribusi sosial yang tercatat',
                2 => 'Pernah terlibat sebagai peserta biasa/volunteer sesekali di 1 kegiatan',
                3 => 'Pernah terlibat sebagai panitia biasa di ≥1 acara kampus/sosial atau volunteer aktif di 1 kegiatan',
                4 => 'Pernah terlibat sebagai panitia inti/koordinator di ≥1 acara kampus/sosial atau volunteer aktif di >1 kegiatan'
            ],
            'jumlah_tanggungan' => [
                1 => 'Jumlah 1',
                2 => 'Jumlah 2',
                3 => 'Jumlah 3',
                4 => 'Jumlah > 3'
            ],
            'semester' => [
                0 => 'Semester <= 2',
                1 => 'Semester 3 - 4',
                2 => 'Semester 5 - 6',
                3 => 'Semester 7 - 8',
                4 => 'Semester > 8'
            ]
        ];
    }
}