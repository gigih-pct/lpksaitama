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
use App\Models\Curriculum;

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

    public function pembelajaran()
    {
        $sensei = Auth::guard('sensei')->user();
        $curriculums = Curriculum::where('sensei_id', $sensei->id)->orderBy('urutan')->get();

        return view('sensei.pembelajaran.index', compact('sensei', 'curriculums'));
    }

    public function penilaian()
    {
        $sensei = Auth::guard('sensei')->user();
        
        // This is a placeholder for grades logic
        // E.g. get all students in sensei's classes and their grades
        $siswas = Siswa::whereIn('class_id', $sensei->classes()->pluck('classes.id'))
            ->with(['grades' => function($q) use ($sensei) {
                $q->where('sensei_id', $sensei->id);
            }])
            ->get();

        $curriculums = Curriculum::where('sensei_id', $sensei->id)->orderBy('urutan')->get();

        return view('sensei.penilaian.index', compact('sensei', 'siswas', 'curriculums'));
    }

    public function evaluasi()
    {
        $sensei = Auth::guard('sensei')->user();
        
        // This is a placeholder for evaluations logic
        $evaluations = Evaluation::where('sensei_id', $sensei->id)
            ->with('siswa')
            ->latest()
            ->get();

        return view('sensei.evaluasi.index', compact('sensei', 'evaluations'));
    }

    public function profil()
    {
        $sensei = Auth::guard('sensei')->user();
        return view('sensei.profil.index', compact('sensei'));
    }
}
