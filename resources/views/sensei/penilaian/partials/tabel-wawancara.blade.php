{{-- Tabel Wawancara --}}
{{-- Materi: Program, Umum, Jepang, Indonesia | Sikap: Cara Duduk, Suara, Fokus --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-medium text-primer">Rekapitulasi Nilai Wawancara</h3>
        <span class="text-xs bg-primer/10 text-primer px-2 py-1 rounded font-medium">{{ $siswas->count() }} Siswa &bull; {{ $evaluasis->count() }} Evaluasi</span>
    </div>
    
    <div class="overflow-x-auto">
        <table id="printable-table" class="w-full text-left text-xs whitespace-nowrap min-w-max">
            <thead class="bg-white border-b border-gray-200 text-gray-600 font-medium">
                <tr>
                    <th class="px-3 py-3 w-10" rowspan="2">No</th>
                    <th class="px-3 py-3 min-w-[130px]" rowspan="2">Nama Siswa</th>
                    @foreach($evaluasis as $eval)
                        <th colspan="9" class="px-2 py-2 border-r border-gray-200 text-center bg-gray-50">
                            <div class="font-medium text-primer">{{ $eval->judul }}</div>
                            <div class="text-[10px] text-gray-400 font-normal">{{ \Carbon\Carbon::parse($eval->tanggal)->format('d M Y') }}</div>
                            <div class="flex gap-1 mt-1.5">
                                <button type="button" @click="openScoreModal({{ $eval->toJson() }})" class="flex-1 text-xs bg-sekunder text-white px-3 py-2.5 rounded shadow-sm hover:bg-sekunder/90 transition-colors truncate font-medium" title="Input Nilai">
                                    Input
                                </button>
                                <button type="button" @click="openEditExamModal({{ $eval->toJson() }}, '{{ $subjectType }}')" class="text-xs bg-sekunder text-white px-3 py-2.5 rounded shadow-sm hover:bg-sekunder/90 transition-colors" title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('sensei.penilaian.evaluasi.destroy', ['type' => $subjectType, 'id' => $eval->id]) }}" method="POST" class="flex" onsubmit="return confirm('Yakin hapus evaluasi ini? Semua nilai di dalamnya akan terhapus!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-sekunder text-white px-3 py-2.5 rounded shadow-sm hover:bg-sekunder/90 transition-colors" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </th>
                    @endforeach
                    <th class="px-3 py-3 bg-gray-50 border-l border-gray-200 w-20 text-center" rowspan="2">Rerata (%)</th>
                </tr>
                <tr class="border-b border-gray-200">
                    @foreach($evaluasis as $eval)
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-100">Prog</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-100">Umum</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-100">Jpng</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-200">Indo</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-100">Ddk</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-100">Suara</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-200">Fokus</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-100">Ket</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 text-gray-700 border-r border-gray-200 max-w-[100px]">Catatan</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($siswas as $index => $siswa)
                    @php
                        $grandTotal = 0;
                        $testCount = 0;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-3 py-2 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 font-medium text-primer">{{ $siswa->nama_lengkap }}</td>
                        
                        @foreach($evaluasis as $eval)
                            @php
                                $nilai = $eval->nilais->where('siswa_id', $siswa->id)->first();
                                $ket = $nilai->ket ?? null;
                                
                                if ($nilai) {
                                    $hasVal = false;
                                    if ($nilai->materi_program !== null) { $grandTotal += $nilai->materi_program; $hasVal = true; }
                                    if ($nilai->materi_umum !== null) { $grandTotal += $nilai->materi_umum; $hasVal = true; }
                                    if ($nilai->materi_jepang !== null) { $grandTotal += $nilai->materi_jepang; $hasVal = true; }
                                    if ($nilai->materi_indonesia !== null) { $grandTotal += $nilai->materi_indonesia; $hasVal = true; }
                                    if ($nilai->sikap_cara_duduk !== null) { $grandTotal += $nilai->sikap_cara_duduk; $hasVal = true; }
                                    if ($nilai->sikap_suara !== null) { $grandTotal += $nilai->sikap_suara; $hasVal = true; }
                                    if ($nilai->sikap_fokus !== null) { $grandTotal += $nilai->sikap_fokus; $hasVal = true; }
                                    if ($hasVal) $testCount += 7; // Assuming 7 components per evaluation session
                                }

                                $ketColor = 'text-gray-300';
                                if ($ket == 'Sangat Baik' || $ket == 'Baik') $ketColor = 'text-primer';
                                elseif ($ket == 'Cukup') $ketColor = 'text-tersier';
                                elseif ($ket == 'Kurang') $ketColor = 'text-sekunder';
                                elseif ($ket == 'Sangat Kurang') $ketColor = 'text-sekunder';
                            @endphp
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $nilai && $nilai->materi_program !== null ? 'font-medium' : 'text-gray-300' }}">{{ $nilai->materi_program ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $nilai && $nilai->materi_umum !== null ? 'font-medium' : 'text-gray-300' }}">{{ $nilai->materi_umum ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $nilai && $nilai->materi_jepang !== null ? 'font-medium' : 'text-gray-300' }}">{{ $nilai->materi_jepang ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-200 {{ $nilai && $nilai->materi_indonesia !== null ? 'font-medium' : 'text-gray-300' }}">{{ $nilai->materi_indonesia ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $nilai && $nilai->sikap_cara_duduk !== null ? 'font-medium' : 'text-gray-300' }}">{{ $nilai->sikap_cara_duduk ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $nilai && $nilai->sikap_suara !== null ? 'font-medium' : 'text-gray-300' }}">{{ $nilai->sikap_suara ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $nilai && $nilai->sikap_fokus !== null ? 'font-medium' : 'text-gray-300' }}">{{ $nilai->sikap_fokus ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 text-xs font-bold {{ $ketColor }}">{{ $ket ?? '-' }}</td>
                            <td class="px-2 py-2 border-r border-gray-200 text-xs text-gray-500 max-w-[100px] truncate" title="{{ $nilai->catatan ?? '' }}">{{ $nilai->catatan ?? '-' }}</td>
                        @endforeach

                        @php
                            $rerata = $testCount > 0 ? round($grandTotal / $testCount, 1) : 0;
                        @endphp
                        <td class="px-3 py-2 text-center bg-sekunder/10 font-bold text-sekunder border-l border-gray-200">{{ $rerata }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-200 font-medium text-xs">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-right bg-gray-50 border-r border-gray-200">Rerata Kelas</td>
                    @foreach($evaluasis as $eval)
                        @php
                            $avgProg = $eval->nilais->count() > 0 ? round($eval->nilais->avg('materi_program'), 1) : 0;
                            $avgUmum = $eval->nilais->count() > 0 ? round($eval->nilais->avg('materi_umum'), 1) : 0;
                            $avgJepang = $eval->nilais->count() > 0 ? round($eval->nilais->avg('materi_jepang'), 1) : 0;
                            $avgIndo = $eval->nilais->count() > 0 ? round($eval->nilais->avg('materi_indonesia'), 1) : 0;
                            $avgDuduk = $eval->nilais->count() > 0 ? round($eval->nilais->avg('sikap_cara_duduk'), 1) : 0;
                            $avgSuara = $eval->nilais->count() > 0 ? round($eval->nilais->avg('sikap_suara'), 1) : 0;
                            $avgFokus = $eval->nilais->count() > 0 ? round($eval->nilais->avg('sikap_fokus'), 1) : 0;
                        @endphp
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-primer">{{ $avgProg }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-primer">{{ $avgUmum }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-primer">{{ $avgJepang }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-200 text-primer">{{ $avgIndo }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-sekunder">{{ $avgDuduk }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-sekunder">{{ $avgSuara }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-sekunder">{{ $avgFokus }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100"></td>
                        <td class="px-2 py-2 text-center border-r border-gray-200"></td>
                    @endforeach
                    <td class="px-3 py-2 text-center bg-sekunder/20 font-bold text-sekunder border-l border-gray-200">{{ $kelasRerataNilai }}%</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-3 bg-white border-t border-gray-100 text-xs text-gray-500">
        <span class="text-primer font-medium">Materi</span>: Program, Umum, Jepang, Indonesia &bull; 
        <span class="text-sekunder font-medium">Sikap</span>: Cara Duduk, Suara, Fokus
    </div>
</div>
