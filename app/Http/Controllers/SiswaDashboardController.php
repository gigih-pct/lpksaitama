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
use Illuminate\Support\Facades\View;

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
        
        return view('siswa.beranda.index', compact('siswa', 'announcements', 'invoices', 'evaluations'));
    }

    public function pembelajaran()
    {
        $siswa = Auth::guard('siswa')->user();
        // Since we don't have attendance yet, we use a dummy percentage in view, but we can pass real subjects
        $subjects = \App\Models\Subject::all();
        
        return view('siswa.pembelajaran.index', compact('siswa', 'subjects'));
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
}
