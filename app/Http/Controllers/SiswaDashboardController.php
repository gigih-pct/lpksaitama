<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;
use App\Models\Evaluation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\StudentEquipment;
use App\Models\StudentDocument;
use App\Models\Announcement;
use App\Models\Siswa;
use App\Models\Banner;
use App\Models\AttendanceSession;
use App\Models\Attendance;
use App\Models\Jadwal;
use App\Models\Kalender;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SiswaDashboardController extends Controller
{
    public function __construct()
    {
        $banners = Banner::where('is_active', true)
            ->where('lokasi', 'Dashboard')
            ->whereIn('role_target', ['Semua', 'Siswa'])
            ->get();
        View::share('dashboard_banners', $banners);
    }
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Data Beranda
        $announcements = Announcement::whereIn('role_target', ['Semua', 'Siswa'])
                            ->orderBy('tanggal', 'desc')
                            ->take(5)->get();
        $invoices = $siswa->invoices()->where('status', 'Belum Lunas')->get();
        $evaluations = $siswa->evaluations()->orderBy('tanggal_evaluasi', 'desc')->take(3)->get();
        $hariIni = \Carbon\Carbon::now()->locale('id')->dayName;
        $jadwals = Jadwal::where('kelas_id', $siswa->class_id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get();
        
        return view('siswa.beranda.index', compact('siswa', 'announcements', 'invoices', 'evaluations', 'jadwals'));
    }

    public function pembelajaran()
    {
        $siswa = Auth::guard('siswa')->user();
        
        $curriculums = \App\Models\Curriculum::where('kelas_id', $siswa->class_id)->orderBy('urutan')->get();
        
        // Dapatkan sesi absen aktif untuk kelas siswa ini hari ini
        $activeSessions = AttendanceSession::whereHas('jadwal', function($q) use ($siswa) {
                $q->where('kelas_id', $siswa->class_id);
            })
            ->where('tanggal', now()->toDateString())
            ->where('is_active', true)
            ->get();
        $jadwals = Jadwal::where('kelas_id', $siswa->class_id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai', 'asc')
            ->get();
            
        $submissions = $siswa->taskSubmissions()->latest()->get();
        $modules = \App\Models\Module::where('kelas_id', $siswa->class_id)
            ->with('subject')
            ->latest()
            ->get();

        return view('siswa.pembelajaran.index', compact('siswa', 'curriculums', 'activeSessions', 'jadwals', 'submissions', 'modules'));
    }

    public function kalender()
    {
        $siswa = Auth::guard('siswa')->user();
        $jadwals = Jadwal::where('kelas_id', $siswa->class_id)->get();
        $kalenders = Kalender::orderBy('tanggal_mulai', 'asc')->get();
        
        return view('siswa.kalender.index', compact('siswa', 'jadwals', 'kalenders'));
    }

    public function nilai()
    {
        $siswa = Auth::guard('siswa')->user();
        $grades = $siswa->grades()->with('subject', 'sensei')->get();
        
        return view('siswa.nilai.index', compact('siswa', 'grades'));
    }

    public function evaluasi()
    {
        $siswa = Auth::guard('siswa')->user();
        $evaluations = $siswa->evaluations()->with('sensei')->orderBy('tanggal_evaluasi', 'desc')->get();
        
        return view('siswa.evaluasi.index', compact('siswa', 'evaluations'));
    }

    public function pembayaran()
    {
        $siswa = Auth::guard('siswa')->user();
        $invoices = $siswa->invoices()->orderBy('jatuh_tempo', 'asc')->get();
        $equipments = $siswa->studentEquipments()->with('equipment')->get();
        
        return view('siswa.pembayaran.index', compact('siswa', 'invoices', 'equipments'));
    }

    public function berkas()
    {
        $siswa = Auth::guard('siswa')->user();
        $documents = $siswa->studentDocuments()->with('documentType')->get();
        
        return view('siswa.berkas.index', compact('siswa', 'documents'));
    }

    public function informasi()
    {
        $siswa = Auth::guard('siswa')->user();
        $announcements = Announcement::whereIn('role_target', ['Semua', 'Siswa'])
                            ->orderBy('tanggal', 'desc')
                            ->get();
                            
        return view('siswa.informasi.index', compact('siswa', 'announcements'));
    }

    public function profil()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.profil.index', compact('siswa'));
    }

    // Method absensi removed

    public function submitHadir(Request $request)
    {
        $request->validate([
            'kode_absen' => 'required|string|size:6'
        ]);

        $siswa = Auth::guard('siswa')->user();

        // Cari sesi absen berdasarkan kode
        $session = AttendanceSession::where('kode_absen', strtoupper($request->kode_absen))
            ->where('tanggal', now()->toDateString())
            ->where('is_active', true)
            ->first();

        if (!$session) {
            return back()->with('error', 'Kode Absen tidak valid atau sesi sudah ditutup.');
        }

        // Pastikan sesi ini untuk kelas siswa tersebut
        if ($session->jadwal->kelas_id != $siswa->class_id) {
            return back()->with('error', 'Kode Absen tidak berlaku untuk kelas Anda.');
        }

        // Cek apakah sudah absen
        $existing = Attendance::where('attendance_session_id', $session->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan absensi untuk sesi ini.');
        }

        // Rekam kehadiran
        Attendance::create([
            'attendance_session_id' => $session->id,
            'siswa_id' => $siswa->id,
            'status' => 'Hadir'
        ]);

        return back()->with('success', 'Berhasil melakukan absensi: Hadir!');
    }

    public function submitIzin(Request $request)
    {
        $request->validate([
            'attendance_session_id' => 'required|exists:attendance_sessions,id',
            'status' => 'required|in:Izin,Sakit',
            'keterangan' => 'required|string|max:255',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $siswa = Auth::guard('siswa')->user();

        $session = AttendanceSession::where('id', $request->attendance_session_id)
            ->where('is_active', true)
            ->first();

        if (!$session) {
            return back()->with('error', 'Sesi absensi sudah ditutup atau tidak ditemukan.');
        }

        // Pastikan kelas sesuai
        if ($session->jadwal->kelas_id != $siswa->class_id) {
            return back()->with('error', 'Sesi ini bukan untuk kelas Anda.');
        }

        // Cek jika sudah absen
        $existing = Attendance::where('attendance_session_id', $session->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah mengajukan absensi untuk sesi ini.');
        }

        // Upload bukti foto
        $path = $request->file('bukti_foto')->store('absensi', 'public');

        Attendance::create([
            'attendance_session_id' => $session->id,
            'siswa_id' => $siswa->id,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'bukti_foto' => $path
        ]);

        return back()->with('success', 'Berhasil mengajukan ' . $request->status . '.');
    }

    public function submitTugas(Request $request)
    {
        $request->validate([
            'judul_tugas' => 'required|string|max:255',
            'bukti_tugas' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ]);

        $siswa = Auth::guard('siswa')->user();
        $file = $request->file('bukti_tugas');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . \Illuminate\Support\Str::slug($request->judul_tugas);
        
        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());
            
            $webpFilename = $filename . '.webp';
            $path = 'tugas/' . $webpFilename;
            
            Storage::disk('public')->put($path, (string) $image->toWebp(80));
            $finalPath = $path;
            $finalExt = 'webp';
        } else {
            $finalPath = $file->storeAs('tugas', $filename . '.' . $extension, 'public');
            $finalExt = $extension;
        }

        TaskSubmission::create([
            'siswa_id' => $siswa->id,
            'judul_tugas' => $request->judul_tugas,
            'file_path' => $finalPath,
            'file_type' => $finalExt,
        ]);

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
