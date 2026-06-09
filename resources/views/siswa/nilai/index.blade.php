@extends('siswa.app')

@section('title', 'Nilai & Performa - LPK Saitama')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Header: Data Diri Lengkap Siswa -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row gap-6 items-center md:items-start relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-primer/5 transform translate-x-10 -translate-y-10 rotate-45"></div>
        <div class="absolute bottom-0 right-10 w-24 h-24 bg-tersier/5 transform translate-y-10 rotate-45"></div>
        
        <div class="relative shrink-0">
            <div class="w-24 h-24 md:w-32 md:h-32 bg-primer p-1 shadow-sm rounded-md">
                <div class="w-full h-full bg-white flex items-center justify-center overflow-hidden rounded-md border-2 border-white">
                    <svg class="w-12 h-12 md:w-16 md:h-16 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 bg-sekunder text-white text-[10px] font-medium px-2 py-0.5 rounded-sm border border-white">Aktif</div>
        </div>
        
        <div class="flex-1 text-center md:text-left z-10 w-full">
            <h1 class="text-2xl md:text-3xl font-medium text-primer tracking-tight mb-1">{{ $siswa->nama_lengkap }}</h1>
            <p class="text-gray-500 font-medium text-sm md:text-base mb-4">Siswa Program Bahasa Jepang - {{ $siswa->kelas?->nama_kelas ?? 'Belum Ditentukan' }}</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-gray-100 pt-4 text-left">
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Nomor Induk</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $siswa->nomor_induk ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Kelas</p>
                    <p class="text-sm text-gray-800 font-medium">{{ $siswa->kelas?->nama_kelas ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Periode</p>
                    <p class="text-sm text-gray-800 font-medium">{{ \App\Models\Batch::find($siswa->kelas?->batch_id)?->tahun ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium mb-0.5">Wali Kelas</p>
                    <p class="text-sm text-gray-800 font-medium">Sensei Pendamping</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Pengumuman Landscape -->
    <div class="bg-primer rounded-md p-4 sm:p-6 text-white shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 flex items-center justify-center rounded-md shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="font-medium text-lg">Hasil Tryout JFT-Basic Bulan Ini</h3>
                <p class="text-white/80 text-sm mt-0.5">Nilai tryout sudah keluar. Periksa transkrip dan analisis kelemahanmu.</p>
            </div>
        </div>
        <a href="#" class="bg-white text-primer px-4 py-2 rounded-md font-medium text-sm hover:bg-gray-50 active:scale-95 transition-all whitespace-nowrap border border-transparent shadow-sm">
            Lihat Transkrip
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Left Column: Summary Nilai -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Skor Akhir -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
                <h3 class="text-lg font-medium text-primer mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-sekunder" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Skor Rata-rata
                </h3>
                
                <div class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-md border border-gray-100 relative">
                    <div class="w-32 h-32 rounded-full border-4 border-primer flex items-center justify-center bg-white shadow-sm">
                        <span class="text-4xl font-medium text-primer">{{ $grades->count() > 0 ? round($grades->avg('nilai')) : 0 }}</span>
                    </div>
                    <span class="mt-4 text-sm font-medium text-gray-600 bg-white px-3 py-1 border border-gray-200 rounded-md shadow-sm">
                        Predikat: {{ $grades->count() > 0 ? ($grades->avg('nilai') >= 85 ? 'Sangat Baik' : 'Baik') : '-' }}
                    </span>
                </div>
            </div>

            <!-- Distribusi Keterampilan -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Distribusi Keterampilan
                    </h3>
                </div>
                
                <div class="space-y-4">
                    @forelse($grades as $grade)
                    <div>
                        <div class="flex justify-between text-xs font-medium mb-1.5">
                            <span class="text-gray-700">{{ $grade->subject->nama_pelajaran }}</span>
                            <span class="text-primer">{{ $grade->nilai }}/100</span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 rounded-sm overflow-hidden">
                            <div class="bg-primer h-full opacity-80" style="width: {{ $grade->nilai }}%"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-sm text-gray-500 py-4">Belum ada data nilai keterampilan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Grafik Performa & Detail Nilai -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Grafik Performa -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        Grafik Performa Nilai
                    </h3>
                    
                    <select class="text-sm font-medium border border-gray-200 text-gray-600 rounded-md py-1.5 px-3 bg-gray-50 outline-none focus:border-primer">
                        <option>3 Bulan Terakhir</option>
                        <option>6 Bulan Terakhir</option>
                        <option>Semua</option>
                    </select>
                </div>

                <div class="h-[250px] w-full">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Riwayat Nilai & Evaluasi -->
            <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-medium text-primer flex items-center">
                        <svg class="w-5 h-5 mr-2 text-tersier" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Riwayat Evaluasi
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($siswa->evaluations()->orderBy('tanggal_evaluasi', 'desc')->take(3)->get() as $evaluasi)
                    <!-- Nilai Item -->
                    <div class="border border-gray-200 bg-white p-4 rounded-md hover:border-primer/30 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ $evaluasi->judul }}</h4>
                        </div>
                        <p class="text-xs text-gray-600 mb-3">Pelaksanaan: {{ \Carbon\Carbon::parse($evaluasi->tanggal_evaluasi)->translatedFormat('d M Y') }}</p>
                        <p class="text-xs text-gray-700 mb-3">{{ $evaluasi->deskripsi }}</p>
                    </div>
                    @empty
                    <div class="text-center text-sm text-gray-500 py-4">Belum ada riwayat evaluasi.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        
        // Setup Chart
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jul', 'Ags', 'Sep', 'Okt'],
                datasets: [{
                    label: 'Nilai Rata-rata',
                    data: [75, 80, 82, 85],
                    borderColor: '#18345C', // primer
                    backgroundColor: 'rgba(24, 52, 92, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#18345C',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#18345C',
                        padding: 10,
                        titleFont: { family: 'Poppins', size: 13 },
                        bodyFont: { family: 'Poppins', size: 12 },
                        displayColors: false,
                        cornerRadius: 4,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 60,
                        max: 100,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Poppins', size: 11 },
                            color: '#6b7280',
                            stepSize: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Poppins', size: 11 },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
