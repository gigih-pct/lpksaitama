{{-- Tabel BKK (Bunpou / Kanji / Kotoba) --}}
<div class="bg-white rounded-md border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-medium text-primer">Rekapitulasi Nilai <span class="uppercase">{{ $subjectType }}</span></h3>
        <span class="text-xs bg-primer/10 text-primer px-2 py-1 rounded font-medium">{{ $siswas->count() }} Siswa &bull; {{ $evaluasis->count() }} Evaluasi</span>
    </div>
    
    <div class="overflow-x-auto">
        <table id="printable-table" class="w-full text-left text-xs whitespace-nowrap min-w-max">
            <thead class="bg-white border-b border-gray-200 text-gray-600 font-medium">
                <tr>
                    <th class="px-4 py-3 w-10">No</th>
                    <th class="px-4 py-3 min-w-[140px]">Nama Siswa</th>
                    @foreach($evaluasis as $eval)
                        <th class="px-3 py-3 border-r border-gray-100 text-center min-w-[110px]">
                            <div class="font-medium text-primer">{{ $eval->judul }}</div>
                            <div class="text-[10px] text-gray-400 font-normal">{{ \Carbon\Carbon::parse($eval->tanggal)->format('d M') }}</div>
                            <div class="text-[10px] bg-gray-100 text-gray-500 rounded mt-1">KKM: {{ $eval->kkm }}</div>
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
                    <th class="px-4 py-3 text-center bg-gray-50 min-w-[80px]">Rata-rata</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($siswas as $index => $siswa)
                    @php
                        $totalPersen = 0;
                        $evalCount = 0;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-primer">{{ $siswa->nama_lengkap }}</td>
                        
                        @foreach($evaluasis as $eval)
                            @php
                                $nilai = $eval->nilais->where('siswa_id', $siswa->id)->first();
                                $nilaiVal = null;
                                $ket = '-';
                                if ($nilai && $nilai->nilai !== null) {
                                    $nilaiVal = $nilai->nilai;
                                    $ket = $nilaiVal >= $eval->kkm ? 'L' : 'TL';
                                    $totalPersen += $nilaiVal;
                                    $evalCount++;
                                }
                            @endphp
                            <td class="px-3 py-2 border-r border-gray-100 text-center">
                                @if($nilaiVal !== null)
                                    <div class="flex flex-col items-center gap-0.5">
                                        <span class="font-bold text-gray-700">{{ $nilaiVal }}</span>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $ket == 'L' ? 'bg-primer/10 text-primer' : 'bg-sekunder/10 text-sekunder' }}">
                                            {{ $ket }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        @endforeach
                        
                        <td class="px-4 py-3 text-center bg-gray-50 font-bold text-primer">
                            {{ $evalCount > 0 ? round($totalPersen / $evalCount) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-200 font-medium text-xs">
                <tr>
                    @php
                        $overallLulus = 0;
                        $overallTl = 0;
                        $overallTotal = 0;
                        foreach($evaluasis as $eval) {
                            $overallLulus += $eval->nilais->filter(function($n) use ($eval) { return $n->nilai !== null && $n->nilai >= $eval->kkm; })->count();
                            $overallTl += $eval->nilais->filter(function($n) use ($eval) { return $n->nilai !== null && $n->nilai < $eval->kkm; })->count();
                            $overallTotal += $eval->nilais->whereNotNull('nilai')->count();
                        }
                    @endphp
                    <td colspan="2" class="px-4 py-2 text-right bg-gray-50 border-r border-gray-200">Total Lulus (L)</td>
                    @foreach($evaluasis as $eval)
                        @php
                            $lulusCount = $eval->nilais->filter(function($n) use ($eval) {
                                return $n->nilai !== null && $n->nilai >= $eval->kkm;
                            })->count();
                        @endphp
                        <td class="px-3 py-2 text-center border-r border-gray-200 text-primer">{{ $lulusCount }}</td>
                    @endforeach
                    <td class="px-3 py-2 text-center border-l border-gray-200 text-primer font-bold">{{ $overallLulus }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="px-4 py-2 text-right bg-gray-50 border-r border-gray-200">Total Tidak Lulus (TL)</td>
                    @foreach($evaluasis as $eval)
                        @php
                            $tlCount = $eval->nilais->filter(function($n) use ($eval) {
                                return $n->nilai !== null && $n->nilai < $eval->kkm;
                            })->count();
                        @endphp
                        <td class="px-3 py-2 text-center border-r border-gray-200 text-sekunder">{{ $tlCount }}</td>
                    @endforeach
                    <td class="px-3 py-2 text-center border-l border-gray-200 text-sekunder font-bold">{{ $overallTl }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="px-4 py-2 text-right bg-gray-50 border-r border-gray-200">Persentase Lulus</td>
                    @foreach($evaluasis as $eval)
                        @php
                            $totalNilai = $eval->nilais->count();
                            $lulusCount = $eval->nilais->filter(function($n) use ($eval) {
                                return $n->nilai !== null && $n->nilai >= $eval->kkm;
                            })->count();
                            $persenLulus = $totalNilai > 0 ? round(($lulusCount / $totalNilai) * 100, 1) : 0;
                        @endphp
                        <td class="px-3 py-2 text-center border-r border-gray-200 {{ $persenLulus >= 75 ? 'text-primer' : 'text-sekunder' }}">
                            {{ $persenLulus }}%
                        </td>
                    @endforeach
                    <td class="px-3 py-2 text-center border-l border-gray-200 font-bold {{ ($overallTotal > 0 ? round(($overallLulus / $overallTotal) * 100, 1) : 0) >= 75 ? 'text-primer' : 'text-sekunder' }}">
                        {{ $overallTotal > 0 ? round(($overallLulus / $overallTotal) * 100, 1) : 0 }}%
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-3 bg-white border-t border-gray-100 text-xs text-gray-500">
        L: Lulus (Nilai >= KKM) &bull; TL: Tidak Lulus
    </div>
</div>
