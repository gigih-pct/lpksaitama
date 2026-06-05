{{-- Tabel FMD (Fisik, Mental, Disiplin) --}}
{{-- Layout per Minggu: MTK, Lari, Push Up, Sit Up, KET, SKOR --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-medium text-primer">Rekapitulasi Nilai FMD</h3>
        <span class="text-xs bg-primer/10 text-primer px-2 py-1 rounded font-medium">{{ $siswas->count() }} Siswa &bull; {{ $evaluasis->count() }} Minggu</span>
    </div>
    
    <div class="overflow-x-auto">
        <table id="printable-table" class="w-full text-left text-xs whitespace-nowrap min-w-max">
            <thead class="bg-white border-b border-gray-200 text-gray-600 font-medium">
                <tr>
                    <th class="px-3 py-3 w-10">No</th>
                    <th class="px-3 py-3 min-w-[130px]">Nama Siswa</th>
                    @foreach($evaluasis as $eval)
                        <th colspan="5" class="px-2 py-2 border-r border-gray-200 text-center bg-gray-50">
                            <div class="font-medium text-primer">{{ $eval->judul }}</div>
                            <div class="text-[10px] text-gray-400 font-normal">{{ \Carbon\Carbon::parse($eval->tanggal)->format('d M') }}</div>
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
                    <th class="px-3 py-3 text-center bg-gray-50">Rerata (%)</th>
                </tr>
                <tr class="border-b border-gray-200">
                    <th colspan="2" class="bg-white border-r border-gray-200"></th>
                    @foreach($evaluasis as $eval)
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 border-r border-gray-100">MTK</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 border-r border-gray-100">Lari</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 border-r border-gray-100">Push</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 border-r border-gray-100">Sit</th>
                        <th class="px-2 py-1 text-center text-[10px] bg-gray-100 border-r border-gray-200">Ket</th>
                    @endforeach
                    <th class="bg-gray-50"></th>
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
                                $mtk = $nilai->skor_mtk ?? null;
                                $lari = $nilai->skor_lari ?? null;
                                $push = $nilai->skor_push_up ?? null;
                                $sit = $nilai->skor_sit_up ?? null;
                                $ket = $nilai->ket ?? null;
                                
                                if ($mtk !== null) { $grandTotal += $mtk; $testCount++; }
                                if ($lari !== null) { $grandTotal += $lari; $testCount++; }
                                if ($push !== null) { $grandTotal += $push; $testCount++; }
                                if ($sit !== null) { $grandTotal += $sit; $testCount++; }
                            @endphp
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $mtk !== null ? 'font-medium' : 'text-gray-300' }}">{{ $mtk ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $lari !== null ? 'font-medium' : 'text-gray-300' }}">{{ $lari ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $push !== null ? 'font-medium' : 'text-gray-300' }}">{{ $push ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-100 {{ $sit !== null ? 'font-medium' : 'text-gray-300' }}">{{ $sit ?? '-' }}</td>
                            <td class="px-2 py-2 text-center border-r border-gray-200 text-xs font-bold {{ $ket == 'L' ? 'text-primer' : ($ket == 'TL' ? 'text-sekunder' : 'text-gray-300') }}">{{ $ket ?? '-' }}</td>
                        @endforeach
                        
                        @php
                            $rerata = $testCount > 0 ? round($grandTotal / $testCount, 1) : 0;
                        @endphp
                        <td class="px-3 py-2 text-center bg-sekunder/10 font-bold text-sekunder">{{ $rerata }}%</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-200 font-medium text-xs">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-right bg-gray-50 border-r border-gray-200">Rerata Kelas</td>
                    @foreach($evaluasis as $eval)
                        @php
                            $avgMtk = $eval->nilais->count() > 0 ? round($eval->nilais->avg('skor_mtk'), 1) : 0;
                            $avgLari = $eval->nilais->count() > 0 ? round($eval->nilais->avg('skor_lari'), 1) : 0;
                            $avgPush = $eval->nilais->count() > 0 ? round($eval->nilais->avg('skor_push_up'), 1) : 0;
                            $avgSit = $eval->nilais->count() > 0 ? round($eval->nilais->avg('skor_sit_up'), 1) : 0;
                        @endphp
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-primer">{{ $avgMtk }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-primer">{{ $avgLari }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-primer">{{ $avgPush }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-100 text-primer">{{ $avgSit }}</td>
                        <td class="px-2 py-2 text-center border-r border-gray-200"></td>
                    @endforeach
                    <td class="px-3 py-2 text-center bg-sekunder/20 font-bold text-sekunder">{{ $kelasRerataNilai }}%</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-3 bg-white border-t border-gray-100 text-xs text-gray-500">
        MTK: Matematika &bull; FMD dinilai per Minggu dengan 4 komponen: MTK, Lari, Push Up, Sit Up
    </div>
</div>
