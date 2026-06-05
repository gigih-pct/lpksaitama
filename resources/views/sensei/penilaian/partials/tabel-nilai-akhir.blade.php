<div class="bg-white p-5 rounded-md border border-gray-100 shadow-sm mb-6 mt-6">
    <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
        <div>
            <h3 class="font-medium text-primer text-lg">Rekapitulasi Nilai Akhir</h3>
            <p class="text-xs text-gray-500 mt-1">Tabel kustom untuk nilai akhir siswa.</p>
        </div>
        <div class="flex items-center space-x-4">
            <form method="GET" action="{{ route('sensei.penilaian') }}" class="flex items-center space-x-2">
                <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
                <input type="hidden" name="bulan" value="{{ $selectedBulan }}">
                <input type="hidden" name="tahun" value="{{ $selectedTahun }}">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-2 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..." class="w-40 text-xs border border-gray-300 rounded pl-7 pr-2 py-1.5 focus:ring-primer focus:border-primer outline-none" onchange="this.form.submit()">
                </div>
                <label class="text-xs font-medium text-gray-600 hidden sm:block">Tampil:</label>
                <select name="per_page_nilai" class="text-xs border border-gray-300 rounded px-2 py-1.5 focus:ring-primer focus:border-primer outline-none" onchange="this.form.submit()">
                    <option value="5" {{ ($perPageNilai ?? 10) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ ($perPageNilai ?? 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="50" {{ ($perPageNilai ?? 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ ($perPageNilai ?? 10) == 100 ? 'selected' : '' }}>100</option>
                </select>
                @if(request('search'))
                    <a href="{{ route('sensei.penilaian', ['kelas_id' => $selectedKelasId, 'subject_id' => $selectedSubjectId, 'bulan' => $selectedBulan, 'tahun' => $selectedTahun]) }}" class="text-xs text-sekunder hover:underline">Reset</a>
                @endif
            </form>
            <button @click="showNilaiAkhirExamModal = true" class="bg-sekunder hover:bg-primer/90 text-white px-3 py-1.5 rounded-md text-xs font-medium transition-colors shadow-sm flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Pelajaran
            </button>
        </div>
    </div>

    <div class="overflow-x-auto relative shadow-sm rounded-md border border-gray-200 max-w-full">
        <table class="w-full text-sm text-left whitespace-nowrap border-collapse">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th rowspan="2" class="px-3 py-3 border-r border-gray-200 bg-gray-50" style="min-width: 50px;">No.</th>
                    <th rowspan="2" class="px-3 py-3 border-r border-gray-200 bg-gray-50" style="min-width: 200px;">Nama Siswa</th>
                    
                    @if(isset($evaluasiNilaiAkhir) && $evaluasiNilaiAkhir->count() > 0)
                        @foreach($evaluasiNilaiAkhir as $eval)
                            @php $subCount = count($eval->kriteria_kolom ?? []); @endphp
                            <th colspan="{{ $subCount > 0 ? $subCount : 1 }}" class="px-3 py-2 border-r border-b border-gray-200 text-center">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <div class="flex items-center justify-center space-x-1">
                                        <span class="font-bold mr-1">{{ $eval->judul }}</span>
                                        <button type="button" @click="openScoreModal({{ $eval->toJson() }})" class="text-sekunder hover:text-primer p-1 bg-white border border-gray-100 rounded-full shadow-sm" title="Input Nilai">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button type="button" @click="openEditNilaiAkhirModal({{ $eval->toJson() }})" class="text-amber-500 hover:text-amber-600 p-1 bg-white border border-gray-100 rounded-full shadow-sm" title="Edit Pelajaran">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <form action="{{ route('sensei.penilaian.evaluasi.destroy', ['type' => 'nilai_akhir', 'id' => $eval->id]) }}" method="POST" onsubmit="return confirm('Hapus pelajaran ini beserta nilainya?')" class="inline-block m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600 p-1 bg-white border border-gray-100 rounded-full shadow-sm" title="Hapus Pelajaran">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </th>
                        @endforeach
                    @else
                        <th rowspan="2" class="px-3 py-3 border-r border-b border-gray-200 text-center text-gray-400 italic font-normal bg-gray-50">Belum ada pelajaran. Klik "Tambah Pelajaran".</th>
                    @endif
                </tr>
                <tr>
                    @if(isset($evaluasiNilaiAkhir) && $evaluasiNilaiAkhir->count() > 0)
                        @foreach($evaluasiNilaiAkhir as $eval)
                            @php $subs = $eval->kriteria_kolom ?? []; @endphp
                            @if(count($subs) > 0)
                                @foreach($subs as $sub)
                                    <th class="px-3 py-2 border-r border-gray-200 text-center" style="min-width: 80px;">{{ is_array($sub) ? ($sub['nama'] ?? '') : $sub }}</th>
                                @endforeach
                            @else
                                <th class="px-3 py-2 border-r border-gray-200 text-center" style="min-width: 80px;">Nilai</th>
                            @endif
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($siswasNilai as $index => $siswa)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-3 py-2 border-r border-gray-200 bg-white">{{ $siswasNilai->firstItem() + $index }}</td>
                        <td class="px-3 py-2 border-r border-gray-200 font-medium bg-white">{{ $siswa->nama_lengkap }}</td>
                        
                        @if(isset($evaluasiNilaiAkhir) && $evaluasiNilaiAkhir->count() > 0)
                            @foreach($evaluasiNilaiAkhir as $eval)
                                @php
                                    $nilaiRow = $eval->nilais->where('siswa_id', $siswa->id)->first();
                                    $subs = $eval->kriteria_kolom ?? [];
                                    $subCount = count($subs);
                                @endphp
                                
                                @if($subCount > 0)
                                    @foreach($subs as $sIdx => $sub)
                                        @php
                                            $val = $nilaiRow && isset($nilaiRow->nilai_data[$sIdx]) ? $nilaiRow->nilai_data[$sIdx] : '-';
                                        @endphp
                                        <td class="px-3 py-2 border-r border-gray-200 text-center font-semibold text-gray-700">{{ $val }}</td>
                                    @endforeach
                                @else
                                    @php
                                        $val = $nilaiRow && isset($nilaiRow->nilai_data[0]) ? $nilaiRow->nilai_data[0] : '-';
                                    @endphp
                                    <td class="px-3 py-2 border-r border-gray-200 text-center font-semibold text-gray-700">{{ $val }}</td>
                                @endif
                            @endforeach
                        @else
                            <td class="px-3 py-2 border-r border-gray-200 bg-gray-50"></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 font-medium">
                <tr>
                    <td colspan="2" class="px-3 py-3 border-r border-t border-gray-200 text-right bg-gray-50 uppercase text-xs tracking-wider">Rerata Kelas</td>
                    @if(isset($evaluasiNilaiAkhir) && $evaluasiNilaiAkhir->count() > 0)
                        @foreach($evaluasiNilaiAkhir as $eval)
                            @php
                                $subs = $eval->kriteria_kolom ?? [];
                                $subCount = count($subs);
                            @endphp
                            
                            @if($subCount > 0)
                                @foreach($subs as $sIdx => $sub)
                                    @php
                                        $sum = 0; $count = 0;
                                        foreach($allSiswas as $siswa) {
                                            $nilaiRow = $eval->nilais->where('siswa_id', $siswa->id)->first();
                                            if ($nilaiRow && isset($nilaiRow->nilai_data[$sIdx])) {
                                                $val = $nilaiRow->nilai_data[$sIdx];
                                                if (is_numeric($val)) {
                                                    $sum += (float)$val;
                                                    $count++;
                                                }
                                            }
                                        }
                                        $avg = $count > 0 ? round($sum / $count, 2) : '-';
                                    @endphp
                                    <td class="px-3 py-3 border-r border-t border-gray-200 text-center text-primer">{{ $avg }}</td>
                                @endforeach
                            @else
                                @php
                                    $sum = 0; $count = 0;
                                    foreach($allSiswas as $siswa) {
                                        $nilaiRow = $eval->nilais->where('siswa_id', $siswa->id)->first();
                                        if ($nilaiRow && isset($nilaiRow->nilai_data[0])) {
                                            $val = $nilaiRow->nilai_data[0];
                                            if (is_numeric($val)) {
                                                $sum += (float)$val;
                                                $count++;
                                            }
                                        }
                                    }
                                    $avg = $count > 0 ? round($sum / $count, 2) : '-';
                                @endphp
                                <td class="px-3 py-3 border-r border-t border-gray-200 text-center text-primer">{{ $avg }}</td>
                            @endif
                        @endforeach
                    @else
                        <td class="px-3 py-3 border-r border-t border-gray-200 bg-gray-50"></td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>

    @if($siswasNilai instanceof \Illuminate\Pagination\LengthAwarePaginator && $siswasNilai->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $siswasNilai->links() }}
        </div>
    @endif
</div>
