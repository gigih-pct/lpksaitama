<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Banner;
use App\Models\Sensei;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Grade;
use App\Models\Evaluation;
use App\Models\Jadwal;
use App\Models\Curriculum;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use Illuminate\Support\Str;

class SenseiDashboardController extends Controller
{
    public function __construct()
    {
        // View Composer for Banners in Sensei Layout
        View::composer('sensei.*', function ($view) {
            $dashboard_banners = Banner::where('is_active', true)
                ->where('lokasi', 'Dashboard')
                ->whereIn('role_target', ['Sensei', 'Semua'])
                ->get();
            $view->with('dashboard_banners', $dashboard_banners);
            
            if (Auth::guard('sensei')->check()) {
                $view->with('sensei', Auth::guard('sensei')->user());
            }
        });
    }

    public function index()
    {
        $sensei = Auth::guard('sensei')->user();
        
        // Overview statistics
        $totalKelas = $sensei->classes()->count();
        // Students from classes sensei teaches
        $totalSiswa = Siswa::whereIn('class_id', $sensei->classes()->pluck('classes.id'))->count();

        // Recent evaluations by this sensei
        $recentEvaluations = Evaluation::where('sensei_id', $sensei->id)
            ->latest()
            ->take(5)
            ->get();

        return view('sensei.beranda.index', compact('sensei', 'totalKelas', 'totalSiswa', 'recentEvaluations'));
    }

    public function kelas()
    {
        $sensei = Auth::guard('sensei')->user();
        $kelases = $sensei->classes()->withCount('siswas')->get();

        return view('sensei.kelas.index', compact('sensei', 'kelases'));
    }

    public function pembelajaran(Request $request)
    {
        $sensei = Auth::guard('sensei')->user();
        
        $kelases = $sensei->classes()->get();
        $kelasIds = $kelases->pluck('id');

        $selectedKelasId = $request->get('kelas_id', null);
        $selectedSubjectId = $request->get('subject_id', null);
        
        $jadwalQuery = Jadwal::whereIn('kelas_id', $kelasIds)
            ->with(['kelas', 'subject'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai', 'asc');

        if ($selectedKelasId) {
            $jadwalQuery->where('kelas_id', $selectedKelasId);
        }
        if ($selectedSubjectId) {
            $jadwalQuery->where('subject_id', $selectedSubjectId);
        }

        $jadwals = $jadwalQuery->get();
            
        $moduleQuery = \App\Models\Module::where('sensei_id', $sensei->id)->latest();
        if ($selectedKelasId) {
            $moduleQuery->where('kelas_id', $selectedKelasId);
        }
        if ($selectedSubjectId) {
            $moduleQuery->where('subject_id', $selectedSubjectId);
        }
        $modules = $moduleQuery->get();

        $subjects = \App\Models\Subject::all();

        return view('sensei.pembelajaran.index', compact('sensei', 'kelases', 'jadwals', 'modules', 'subjects', 'selectedKelasId', 'selectedSubjectId'));
    }

    public function penilaian(Request $request)
    {
        $sensei = Auth::guard('sensei')->user();
        
        $kelases = $sensei->classes()->with('siswas')->get();
        $subjects = \App\Models\Subject::all();

        $selectedKelasId = $request->get('kelas_id');
        if (!$selectedKelasId && $kelases->isNotEmpty()) {
            $selectedKelasId = $kelases->first()->id;
        }

        $selectedSubjectId = $request->get('subject_id', null);
        $searchQuery = $request->get('search', '');
        
        $selectedBulan = $request->get('bulan', date('m'));
        $selectedTahun = $request->get('tahun', date('Y'));
        
        $perPageAbsensi = $request->get('per_page_absensi', 10);
        $perPageNilai = $request->get('per_page_nilai', 10);

        $siswasAbsensi = collect();
        $siswasNilai = collect();
        $allSiswas = collect();
        $evaluasis = collect();
        $subjectType = null;
        
        $absensiData = collect();
        
        $kelasRerataNilai = 0;
        $kelasRerataAbsensi = 0;
        $chartLabels = [];
        $chartNilaiData = [];
        $chartAbsensiData = [];

        if ($selectedKelasId) {
            $siswaQuery = \App\Models\Siswa::where('class_id', $selectedKelasId);
            if ($searchQuery) {
                $siswaQuery->where('nama_lengkap', 'like', '%' . $searchQuery . '%');
            }
            $allSiswas = \App\Models\Siswa::where('class_id', $selectedKelasId)->get();
            $siswasAbsensi = (clone $siswaQuery)->paginate($perPageAbsensi, ['*'], 'page_absensi')->appends($request->query());
            $siswasNilai = (clone $siswaQuery)->paginate($perPageNilai, ['*'], 'page_nilai')->appends($request->query());

            // Absensi calculations (GENERAL, NOT BY SUBJECT)
            $jadwalIds = \App\Models\Jadwal::where('kelas_id', $selectedKelasId)->pluck('id');
            $sessionQuery = \App\Models\AttendanceSession::whereIn('jadwal_id', $jadwalIds);
            
            if ($selectedBulan !== 'all' && $selectedTahun !== 'all') {
                $sessionQuery->whereMonth('tanggal', $selectedBulan)->whereYear('tanggal', $selectedTahun);
            }
            
            $sessions = $sessionQuery->orderBy('tanggal', 'asc')->get();
            $sessionIds = $sessions->pluck('id');
            $totalSessions = $sessionIds->count();

            $attendances = \App\Models\Attendance::whereIn('attendance_session_id', $sessionIds)
                ->get()
                ->groupBy('siswa_id');

            $totalClassHadir = 0;
            foreach ($allSiswas as $siswa) {
                $siswaAttendances = $attendances->get($siswa->id) ?? collect();
                $hadir = $siswaAttendances->where('status', 'Hadir')->count();
                $izin = $siswaAttendances->where('status', 'Izin')->count();
                $sakit = $siswaAttendances->where('status', 'Sakit')->count();
                $alpa = $totalSessions - ($hadir + $izin + $sakit);

                $persentase = $totalSessions > 0 ? round(($hadir / $totalSessions) * 100) : 0;
                $totalClassHadir += $hadir;

                $absensiData->put($siswa->id, [
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpa' => $alpa,
                    'persentase' => $persentase,
                    'total_sessions' => $totalSessions
                ]);
            }
            $kelasRerataAbsensi = ($totalSessions > 0 && $allSiswas->count() > 0) ? round(($totalClassHadir / ($totalSessions * $allSiswas->count())) * 100, 2) : 0;

            if ($selectedSubjectId) {
                $subject = \App\Models\Subject::find($selectedSubjectId);
                $subjectType = $subject ? $subject->type_key : null;

                if ($subjectType) {
                    // Filter Evaluasi by month/year
                    $evaluasis = $this->getEvaluasiByType($subjectType, $selectedKelasId)
                        ->filter(function($ev) use ($selectedBulan, $selectedTahun) {
                            $date = \Carbon\Carbon::parse($ev->tanggal);
                            if ($selectedBulan && $selectedBulan !== 'all' && $date->format('m') !== str_pad($selectedBulan, 2, '0', STR_PAD_LEFT)) return false;
                            if ($selectedTahun && $selectedTahun !== 'all' && $date->format('Y') !== (string)$selectedTahun) return false;
                            return true;
                        })->values();

                    // Calculate Chart Data for Nilai (Average per evaluation)
                    $totalNilaiClass = 0;
                    $totalEvalCount = 0;

                    foreach ($evaluasis as $ev) {
                        $chartLabels[] = \Carbon\Carbon::parse($ev->tanggal)->format('d M');
                        $avgEval = 0;
                        if ($ev->nilais && $ev->nilais->count() > 0) {
                            if (in_array($subjectType, ['bunpou', 'kanji', 'kotoba'])) {
                                $avgEval = $ev->nilais->avg('nilai');
                            } elseif ($subjectType === 'fmd') {
                                $avgEval = $ev->nilais->map(function($n) { 
                                    $count = 0;
                                    $sum = 0;
                                    if ($n->skor_mtk !== null) { $sum += $n->skor_mtk; $count++; }
                                    if ($n->skor_lari !== null) { $sum += $n->skor_lari; $count++; }
                                    if ($n->skor_push_up !== null) { $sum += $n->skor_push_up; $count++; }
                                    if ($n->skor_sit_up !== null) { $sum += $n->skor_sit_up; $count++; }
                                    return $count > 0 ? $sum / $count : 0;
                                })->avg();
                            } elseif ($subjectType === 'wawancara') {
                                $avgEval = $ev->nilais->map(function($n) {
                                    $count = 0;
                                    $sum = 0;
                                    $fields = ['materi_program', 'materi_umum', 'materi_jepang', 'materi_indonesia', 'sikap_cara_duduk', 'sikap_suara', 'sikap_fokus'];
                                    foreach ($fields as $f) {
                                        if ($n->$f !== null) { $sum += $n->$f; $count++; }
                                    }
                                    return $count > 0 ? $sum / $count : 0;
                                })->avg();
                            }
                        }
                        $chartNilaiData[] = round($avgEval, 2);
                        
                        if ($avgEval > 0) {
                            $totalNilaiClass += $avgEval;
                            $totalEvalCount++;
                        }
                    }
                    $kelasRerataNilai = $totalEvalCount > 0 ? round($totalNilaiClass / $totalEvalCount, 2) : 0;
                }
            }

            // Absensi is now removed from the subject chart per user request
            $chartAbsensiData = [];
            // Sort labels chronologically 
            if (count($chartLabels) > 0) {
                array_multisort(array_map('strtotime', $chartLabels), $chartLabels, $chartNilaiData);
            }
        }

        $evaluasiNilaiAkhir = \App\Models\EvaluasiNilaiAkhir::where('kelas_id', $selectedKelasId)
            ->with('nilais')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('sensei.penilaian.index', compact(
            'sensei', 'kelases', 'subjects',
            'selectedKelasId', 'selectedSubjectId', 'selectedBulan', 'selectedTahun',
            'siswasAbsensi', 'siswasNilai', 'allSiswas', 'evaluasis', 'subjectType', 'searchQuery', 'absensiData',
            'kelasRerataNilai', 'kelasRerataAbsensi', 'chartLabels', 'chartNilaiData', 'chartAbsensiData',
            'perPageAbsensi', 'perPageNilai', 'evaluasiNilaiAkhir'
        ));
    }

    /**
     * Get evaluasi records by subject type
     */
    private function getEvaluasiByType(string $type, int $kelasId)
    {
        $modelMap = [
            'bunpou'    => \App\Models\EvaluasiBunpou::class,
            'kanji'     => \App\Models\EvaluasiKanji::class,
            'kotoba'    => \App\Models\EvaluasiKotoba::class,
            'fmd'       => \App\Models\EvaluasiFmd::class,
            'wawancara' => \App\Models\EvaluasiWawancara::class,
            'nilai_akhir' => \App\Models\EvaluasiNilaiAkhir::class,
        ];

        $model = $modelMap[$type] ?? null;
        if (!$model) return collect();

        return $model::where('kelas_id', $kelasId)
            ->with('nilais')
            ->orderBy('tanggal', 'asc')
            ->get();
    }

    public function evaluasi()
    {
        $sensei = Auth::guard('sensei')->user();
        $kelases = $sensei->classes()->with('siswas')->get();
        
        $exams = \App\Models\Exam::where('sensei_id', $sensei->id)
            ->with('kelas', 'subject', 'scores.siswa')
            ->latest()
            ->get();
            
        $subjects = \App\Models\Subject::all();

        return view('sensei.evaluasi.index', compact('sensei', 'kelases', 'exams', 'subjects'));
    }

    public function profil()
    {
        $sensei = Auth::guard('sensei')->user();
        return view('sensei.profil.index', compact('sensei'));
    }

    public function generateAbsensi(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'waktu_berakhir' => 'required|date_format:H:i'
        ]);

        $sensei = Auth::guard('sensei')->user();
        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        // Check if an active session already exists for today
        $session = AttendanceSession::where('jadwal_id', $jadwal->id)
            ->where('tanggal', now()->toDateString())
            ->where('is_active', true)
            ->first();

        if (!$session) {
            $session = AttendanceSession::create([
                'jadwal_id' => $jadwal->id,
                'kode_absen' => strtoupper(Str::random(6)),
                'tanggal' => now()->toDateString(),
                'waktu_berakhir' => $request->waktu_berakhir,
                'is_active' => true
            ]);
        } else {
            // Update waktu_berakhir if session exists
            $session->update(['waktu_berakhir' => $request->waktu_berakhir]);
        }

        return back()->with('absensi_session_id', $session->id);
    }

    public function showAbsensi($session_id)
    {
        $sensei = Auth::guard('sensei')->user();
        $session = AttendanceSession::where('id', $session_id)->firstOrFail();

        // Check if session has expired, if so, mark remaining as Alpa and close session
        if ($session->is_active && $session->waktu_berakhir) {
            $endDateTime = \Carbon\Carbon::parse($session->tanggal . ' ' . $session->waktu_berakhir);
            if ($endDateTime->isPast()) {
                $siswaIds = Siswa::where('class_id', $session->jadwal->kelas_id)->pluck('id');
                $attendedIds = Attendance::where('attendance_session_id', $session->id)->pluck('siswa_id');
                $missingIds = $siswaIds->diff($attendedIds);

                foreach ($missingIds as $id) {
                    Attendance::create([
                        'attendance_session_id' => $session->id,
                        'siswa_id' => $id,
                        'status' => 'Alpa',
                    ]);
                }
                $session->update(['is_active' => false]);
            }
        }
            
        $jadwal = $session->jadwal;
        $sessions = AttendanceSession::where('jadwal_id', $jadwal->id)->latest()->get();

        return view('sensei.pembelajaran.absensi', compact('sensei', 'jadwal', 'sessions', 'session'));
    }

    public function liveAbsensi($session_id)
    {
        $sensei = Auth::guard('sensei')->user();
        $session = AttendanceSession::where('id', $session_id)->firstOrFail();

        // Check if session has expired
        if ($session->is_active && $session->waktu_berakhir) {
            $endDateTime = \Carbon\Carbon::parse($session->tanggal . ' ' . $session->waktu_berakhir);
            if ($endDateTime->isPast()) {
                $siswaIds = Siswa::where('class_id', $session->jadwal->kelas_id)->pluck('id');
                $attendedIds = Attendance::where('attendance_session_id', $session->id)->pluck('siswa_id');
                $missingIds = $siswaIds->diff($attendedIds);

                foreach ($missingIds as $id) {
                    Attendance::create([
                        'attendance_session_id' => $session->id,
                        'siswa_id' => $id,
                        'status' => 'Alpa',
                    ]);
                }
                $session->update(['is_active' => false]);
            }
        }

        $attendances = Attendance::where('attendance_session_id', $session->id)
            ->with('siswa')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($a) {
                return [
                    'id' => $a->id,
                    'siswa_nama' => $a->siswa->nama_lengkap,
                    'status' => $a->status,
                    'waktu' => $a->created_at->format('H:i'),
                    'alasan' => $a->alasan ?? $a->keterangan,
                    'bukti_foto_url' => $a->bukti_foto ? asset('storage/absensi/' . $a->bukti_foto) : null
                ];
            });

        return response()->json([
            'session' => $session,
            'attendances' => $attendances
        ]);
    }

    public function storeMateri(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|max:10240' // 10MB
        ]);

        $sensei = Auth::guard('sensei')->user();
        $file = $request->file('file');
        $filename = time() . '_' . \Illuminate\Support\Str::slug($request->judul) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('modul', $filename, 'public');

        \App\Models\Module::create([
            'kelas_id' => $request->kelas_id,
            'subject_id' => $request->subject_id,
            'sensei_id' => $sensei->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'tipe_file' => $file->getClientOriginalExtension()
        ]);

        return back()->with('success', 'Materi berhasil ditambahkan.');
    }

    public function destroyMateri($id)
    {
        $materi = \App\Models\Module::findOrFail($id);
        if ($materi->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($materi->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($materi->file_path);
        }
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus.');
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kegiatan' => 'required|string|max:255',
        ]);

        \App\Models\Jadwal::create($request->all());

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroyJadwal($id)
    {
        $jadwal = \App\Models\Jadwal::findOrFail($id);
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Store a new evaluasi (Bunpou/Kanji/Kotoba/FMD/Wawancara)
     */
    public function storeEvaluasi(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:classes,id',
            'subject_type' => 'required|in:bunpou,kanji,kotoba,fmd,wawancara,nilai_akhir',
            'kategori' => 'required_unless:subject_type,nilai_akhir|string|max:255',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required_unless:subject_type,nilai_akhir|date|nullable',
        ]);

        $sensei = Auth::guard('sensei')->user();
        $type = $request->subject_type;

        $data = [
            'kelas_id' => $request->kelas_id,
            'sensei_id' => $sensei->id,
            'kategori' => $request->kategori ?? '-',
            'judul' => $request->judul,
            'tanggal' => $request->tanggal ?? date('Y-m-d'),
        ];

        switch ($type) {
            case 'bunpou':
            case 'kanji':
            case 'kotoba':
                $request->validate([
                    'kkm' => 'required|integer|min:0|max:100',
                ]);
                $data['kkm'] = $request->kkm;
                $modelMap = [
                    'bunpou' => \App\Models\EvaluasiBunpou::class,
                    'kanji'  => \App\Models\EvaluasiKanji::class,
                    'kotoba' => \App\Models\EvaluasiKotoba::class,
                ];
                $modelMap[$type]::create($data);
                break;

            case 'fmd':
                $request->validate(['kkm' => 'required|integer|min:0|max:100']);
                $data['kkm'] = $request->kkm;
                \App\Models\EvaluasiFmd::create($data);
                break;

            case 'wawancara':
                \App\Models\EvaluasiWawancara::create($data);
                break;

            case 'nilai_akhir':
                $data['kriteria_kolom'] = json_decode($request->kriteria_kolom, true) ?? [];
                \App\Models\EvaluasiNilaiAkhir::create($data);
                break;
        }

        // Redirect back to penilaian with the same filters
        $subjectMap = [
            'bunpou' => 1, 'kanji' => 2, 'kotoba' => 3, 'fmd' => 4, 'wawancara' => 5,
        ];
        return redirect()->route('sensei.penilaian', [
            'kelas_id' => $request->kelas_id,
            'subject_id' => $subjectMap[$type] ?? null
        ])->with('success', 'Evaluasi baru berhasil dibuat.');
    }

    public function updateEvaluasi(Request $request, $type, $id)
    {
        $rules = [
            'judul' => 'required|string|max:255',
        ];
        
        if ($type !== 'nilai_akhir') {
            $rules['kategori'] = 'required|string|max:255';
            $rules['tanggal'] = 'required|date';
        }
        
        $request->validate($rules);

        $modelMap = [
            'bunpou' => \App\Models\EvaluasiBunpou::class,
            'kanji'  => \App\Models\EvaluasiKanji::class,
            'kotoba' => \App\Models\EvaluasiKotoba::class,
            'fmd'    => \App\Models\EvaluasiFmd::class,
            'wawancara' => \App\Models\EvaluasiWawancara::class,
            'nilai_akhir' => \App\Models\EvaluasiNilaiAkhir::class,
        ];

        if (!array_key_exists($type, $modelMap)) {
            abort(404);
        }

        $eval = $modelMap[$type]::findOrFail($id);
        
        $data = [
            'kategori' => $request->kategori ?? '-',
            'judul' => $request->judul,
        ];
        if ($request->has('tanggal') && $request->tanggal) {
            $data['tanggal'] = $request->tanggal;
        }

        if (in_array($type, ['bunpou', 'kanji', 'kotoba', 'fmd'])) {
            $request->validate(['kkm' => 'required|integer|min:0|max:100']);
            $data['kkm'] = $request->kkm;
        } elseif ($type === 'nilai_akhir' && $request->has('kriteria_kolom')) {
            $data['kriteria_kolom'] = json_decode($request->kriteria_kolom, true) ?? [];
        }

        $eval->update($data);

        return back()->with('success', 'Evaluasi berhasil diperbarui.');
    }

    public function destroyEvaluasi($type, $id)
    {
        $modelMap = [
            'bunpou' => \App\Models\EvaluasiBunpou::class,
            'kanji'  => \App\Models\EvaluasiKanji::class,
            'kotoba' => \App\Models\EvaluasiKotoba::class,
            'fmd'    => \App\Models\EvaluasiFmd::class,
            'wawancara' => \App\Models\EvaluasiWawancara::class,
            'nilai_akhir' => \App\Models\EvaluasiNilaiAkhir::class,
        ];

        if (!array_key_exists($type, $modelMap)) {
            abort(404);
        }

        $eval = $modelMap[$type]::findOrFail($id);
        
        // Delete related nilai first
        $eval->nilais()->delete();
        $eval->delete();

        return back()->with('success', 'Evaluasi beserta semua nilainya berhasil dihapus.');
    }

    /**
     * Store scores for a specific evaluasi type
     */
    public function storeNilai(Request $request)
    {
        $request->validate([
            'subject_type' => 'required|in:bunpou,kanji,kotoba,fmd,wawancara,nilai_akhir',
            'evaluasi_id' => 'required|integer',
            'nilais' => 'required|array',
        ]);

        $type = $request->subject_type;

        switch ($type) {
            case 'bunpou':
            case 'kanji':
            case 'kotoba':
                $this->storeNilaiBKK($type, $request->evaluasi_id, $request->nilais);
                break;
            case 'fmd':
                $this->storeNilaiFmd($request->evaluasi_id, $request->nilais);
                break;
            case 'wawancara':
                $this->storeNilaiWawancara($request->evaluasi_id, $request->nilais);
                break;
            case 'nilai_akhir':
                foreach ($request->nilais as $siswaId => $data) {
                    $nilaiData = is_array($data['nilai_data'] ?? null) ? $data['nilai_data'] : [];
                    \App\Models\NilaiAkhir::updateOrCreate(
                        ['evaluasi_id' => $request->evaluasi_id, 'siswa_id' => $siswaId],
                        [
                            'nilai_data' => $nilaiData,
                            'catatan' => $data['catatan'] ?? null,
                        ]
                    );
                }
                break;
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    private function storeNilaiBKK(string $type, int $evaluasiId, array $nilais)
    {
        $modelMap = [
            'bunpou' => [\App\Models\NilaiBunpou::class, 'evaluasi_bunpou_id'],
            'kanji'  => [\App\Models\NilaiKanji::class, 'evaluasi_kanji_id'],
            'kotoba' => [\App\Models\NilaiKotoba::class, 'evaluasi_kotoba_id'],
        ];

        [$model, $fk] = $modelMap[$type];

        foreach ($nilais as $data) {
            if (isset($data['nilai']) && $data['nilai'] !== '' && $data['nilai'] !== null) {
                $model::updateOrCreate(
                    [$fk => $evaluasiId, 'siswa_id' => $data['siswa_id']],
                    ['nilai' => (int) $data['nilai']]
                );
            }
        }
    }

    private function storeNilaiFmd(int $evaluasiId, array $nilais)
    {
        foreach ($nilais as $data) {
            $hasData = !empty($data['skor_mtk']) || !empty($data['skor_lari']) 
                    || !empty($data['skor_push_up']) || !empty($data['skor_sit_up']) || !empty($data['ket']);
            if ($hasData) {
                \App\Models\NilaiFmd::updateOrCreate(
                    ['evaluasi_fmd_id' => $evaluasiId, 'siswa_id' => $data['siswa_id']],
                    [
                        'skor_mtk' => $data['skor_mtk'] ?? null,
                        'skor_lari' => $data['skor_lari'] ?? null,
                        'skor_push_up' => $data['skor_push_up'] ?? null,
                        'skor_sit_up' => $data['skor_sit_up'] ?? null,
                        'ket' => $data['ket'] ?? null,
                    ]
                );
            }
        }
    }

    private function storeNilaiWawancara(int $evaluasiId, array $nilais)
    {
        foreach ($nilais as $data) {
            $hasData = !empty($data['materi_program']) || !empty($data['sikap_cara_duduk']) || !empty($data['ket']) || !empty($data['catatan']);
            if ($hasData) {
                \App\Models\NilaiWawancara::updateOrCreate(
                    ['eval_wawancara_id' => $evaluasiId, 'siswa_id' => $data['siswa_id']],
                    [
                        'materi_program' => $data['materi_program'] ?? null,
                        'materi_umum' => $data['materi_umum'] ?? null,
                        'materi_jepang' => $data['materi_jepang'] ?? null,
                        'materi_indonesia' => $data['materi_indonesia'] ?? null,
                        'sikap_cara_duduk' => $data['sikap_cara_duduk'] ?? null,
                        'sikap_suara' => $data['sikap_suara'] ?? null,
                        'sikap_fokus' => $data['sikap_fokus'] ?? null,
                        'ket' => $data['ket'] ?? null,
                        'catatan' => $data['catatan'] ?? null,
                    ]
                );
            }
        }
    }

    // Legacy method - keep for backward compatibility with old evaluasi page
    public function storeEvaluasiOld(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'kategori' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kkm' => 'required|integer|min:0|max:100'
        ]);

        $sensei = Auth::guard('sensei')->user();

        \App\Models\Exam::create([
            'kelas_id' => $request->kelas_id,
            'subject_id' => $request->subject_id,
            'sensei_id' => $sensei->id,
            'kategori' => $request->kategori,
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'kkm' => $request->kkm
        ]);

        return back()->with('success', 'Evaluasi baru berhasil dibuat.');
    }

    public function storeEvaluasiScore(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'scores' => 'required|array',
            'scores.*.siswa_id' => 'required|exists:siswas,id',
            'scores.*.benar' => 'nullable|integer|min:0',
            'scores.*.total_soal' => 'nullable|integer|min:1'
        ]);

        $exam = \App\Models\Exam::findOrFail($request->exam_id);
        
        foreach ($request->scores as $scoreData) {
            if (isset($scoreData['benar']) && isset($scoreData['total_soal']) && $scoreData['total_soal'] > 0) {
                $scoreValue = ($scoreData['benar'] / $scoreData['total_soal']) * 100;
                $keterangan = $scoreValue >= $exam->kkm ? 'L' : 'TL';
                
                \App\Models\ExamScore::updateOrCreate(
                    ['exam_id' => $exam->id, 'siswa_id' => $scoreData['siswa_id']],
                    [
                        'score' => $scoreValue,
                        'benar' => $scoreData['benar'],
                        'total_soal' => $scoreData['total_soal'],
                        'keterangan' => $keterangan
                    ]
                );
            }
        }

        return back()->with('success', 'Nilai evaluasi berhasil disimpan.');
    }
}
