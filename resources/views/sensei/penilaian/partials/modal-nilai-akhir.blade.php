<template x-teleport="body">
    <!-- MODAL: BUAT PELAJARAN NILAI AKHIR -->
    <div x-show="showNilaiAkhirExamModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-10 text-center">
            <div x-show="showNilaiAkhirExamModal" @click="showNilaiAkhirExamModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
            <div x-show="showNilaiAkhirExamModal" x-transition.scale.origin.bottom class="w-full max-w-2xl p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                <div class="p-5 bg-primer text-white flex justify-between items-center rounded-t-xl">
                    <h3 class="text-lg font-medium">Tambah Pelajaran Nilai Akhir</h3>
                    <button @click="showNilaiAkhirExamModal = false" type="button" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="{{ route('sensei.penilaian.evaluasi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                    <input type="hidden" name="subject_type" value="nilai_akhir">
                    <input type="hidden" name="kriteria_kolom" :value="JSON.stringify(kriteriaBuilder)">
                    
                    <div class="p-5 overflow-y-auto max-h-[65vh]">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pelajaran</label>
                            <input type="text" name="judul" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer" placeholder="Contoh: Hiragana, Tata Bahasa" required>
                        </div>

                        <div class="border-t border-gray-200 pt-5">
                            <h4 class="font-medium text-primer mb-3 flex items-center justify-between">
                                <span>Sub-Kolom Penilaian</span>
                                <button type="button" @click="kriteriaBuilder.push('Kolom Baru')" class="bg-sekunder hover:bg-sekunder/90 text-white px-3 py-1.5 rounded text-xs font-medium">
                                    + Tambah Kolom
                                </button>
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Tambahkan sub-kolom untuk pelajaran ini (misal: Nilai Baca, Nilai Tulis, Huruf).</p>
                            
                            <div class="space-y-3">
                                <template x-for="(col, idx) in kriteriaBuilder" :key="idx">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" x-model="kriteriaBuilder[idx]" class="w-full border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-primer focus:border-primer" placeholder="Nama Sub Kolom (contoh: Nilai Tulis)">
                                        <button type="button" @click="kriteriaBuilder.splice(idx, 1)" class="text-red-400 hover:text-red-600 p-2 bg-red-50 hover:bg-red-100 rounded-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                <div x-show="kriteriaBuilder.length === 0" class="text-sm text-gray-500 italic p-3 bg-gray-50 rounded border border-dashed border-gray-300 text-center">
                                    Tidak ada sub-kolom. Tabel hanya akan menampilkan 1 kolom utama.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" @click="showNilaiAkhirExamModal = false" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="bg-primer hover:bg-primer/90 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                            Simpan Pelajaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT PELAJARAN NILAI AKHIR -->
    <div x-show="showEditNilaiAkhirExamModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-10 text-center">
            <div x-show="showEditNilaiAkhirExamModal" @click="showEditNilaiAkhirExamModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
            <div x-show="showEditNilaiAkhirExamModal" x-transition.scale.origin.bottom class="w-full max-w-2xl p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                <div class="p-5 bg-primer text-white flex justify-between items-center rounded-t-xl">
                    <h3 class="text-lg font-medium">Edit Pelajaran Nilai Akhir</h3>
                    <button @click="showEditNilaiAkhirExamModal = false" type="button" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form :action="`/sensei/penilaian/evaluasi/nilai_akhir/${editNilaiAkhirData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="kriteria_kolom" :value="JSON.stringify(editKriteriaBuilder)">
                    
                    <div class="p-5 overflow-y-auto max-h-[65vh]">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pelajaran</label>
                            <input type="text" name="judul" x-model="editNilaiAkhirData.judul" class="w-full border border-gray-300 rounded-md py-2.5 px-3 focus:ring-primer focus:border-primer" required>
                        </div>

                        <div class="border-t border-gray-200 pt-5">
                            <h4 class="font-medium text-primer mb-3 flex items-center justify-between">
                                <span>Sub-Kolom Penilaian</span>
                                <button type="button" @click="editKriteriaBuilder.push('Kolom Baru')" class="bg-sekunder hover:bg-sekunder/90 text-white px-3 py-1.5 rounded text-xs font-medium">
                                    + Tambah Kolom
                                </button>
                            </h4>
                            <p class="text-xs text-gray-500 mb-4">Ubah sub-kolom. Perhatian: Mengubah urutan/menghapus kolom bisa membuat nilai lama tidak sinkron dengan judul kolomnya.</p>
                            
                            <div class="space-y-3">
                                <template x-for="(col, idx) in editKriteriaBuilder" :key="idx">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" x-model="editKriteriaBuilder[idx]" class="w-full border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-primer focus:border-primer">
                                        <button type="button" @click="editKriteriaBuilder.splice(idx, 1)" class="text-red-400 hover:text-red-600 p-2 bg-red-50 hover:bg-red-100 rounded-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                <div x-show="editKriteriaBuilder.length === 0" class="text-sm text-gray-500 italic p-3 bg-gray-50 rounded border border-dashed border-gray-300 text-center">
                                    Tidak ada sub-kolom. Tabel hanya akan menampilkan 1 kolom utama.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-5 py-4 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                        <button type="button" @click="showEditNilaiAkhirExamModal = false" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="bg-primer hover:bg-primer/90 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- MODAL: INPUT NILAI AKHIR -->
    <div x-show="showNilaiAkhirScoreModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-10 text-center">
            <div x-show="showNilaiAkhirScoreModal" @click="showNilaiAkhirScoreModal = false" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900/75" aria-hidden="true"></div>
            <div x-show="showNilaiAkhirScoreModal" x-transition.scale.origin.bottom class="w-full p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-xl relative z-10">
                
                <div class="p-5 bg-primer text-white flex justify-between items-center rounded-t-xl">
                    <div>
                        <h3 class="text-lg font-medium" x-text="'Input Nilai Pelajaran: ' + (currentEval ? currentEval.judul : '')"></h3>
                    </div>
                    <button @click="showNilaiAkhirScoreModal = false" type="button" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-4 md:p-6 pb-2">
                    <!-- Search and Pagination Controls -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 p-3 rounded-lg border border-gray-100 mb-4">
                        <div class="w-full sm:w-1/3">
                            <input type="text" x-model="modalSearch" placeholder="Cari nama siswa..." class="w-full border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-primer focus:border-primer">
                        </div>
                        <div class="flex items-center gap-3 text-sm w-full sm:w-auto justify-end">
                            <label class="text-gray-600">Tampil:</label>
                            <select x-model="modalPerPage" @change="modalCurrentPage = 1" class="border border-gray-300 rounded-md py-1.5 px-3 focus:ring-primer focus:border-primer bg-white">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-0 overflow-y-auto overflow-x-auto max-h-[60vh] bg-white relative">
                    <form action="{{ route('sensei.penilaian.nilai.store') }}" method="POST" id="nilaiAkhirForm">
                        @csrf
                        <input type="hidden" name="subject_type" value="nilai_akhir">
                        <input type="hidden" name="evaluasi_id" :value="currentEval ? currentEval.id : ''">

                        <table class="w-full text-sm text-left whitespace-nowrap min-w-max border-collapse">
                            <thead class="text-xs text-white uppercase bg-primer sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-3 py-3 border-r border-primer/20 w-16 text-center">No.</th>
                                    <th class="px-3 py-3 border-r border-primer/20 min-w-[200px]">Nama Siswa</th>
                                    
                                    <template x-if="currentEval && currentEval.kriteria_kolom && currentEval.kriteria_kolom.length > 0">
                                        <template x-for="(sub, idx) in currentEval.kriteria_kolom" :key="idx">
                                            <th class="px-3 py-3 border-r border-primer/20 text-center" x-text="sub"></th>
                                        </template>
                                    </template>
                                    <template x-if="currentEval && (!currentEval.kriteria_kolom || currentEval.kriteria_kolom.length === 0)">
                                        <th class="px-3 py-3 border-r border-primer/20 text-center">Nilai</th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(siswa, index) in paginatedStudents" :key="siswa.id">
                                    <tr class="border-b">
                                        <td class="px-3 py-3 text-center text-gray-500 border-r border-gray-100" x-text="index + 1 + ((modalCurrentPage - 1) * modalPerPage)"></td>
                                        <td class="px-3 py-3 font-medium text-primer border-r border-gray-100">
                                            <span x-text="siswa.nama"></span>
                                            <input type="hidden" :name="'nilais['+index+'][siswa_id]'" :value="siswa.id">
                                        </td>
                                        
                                        <template x-if="currentEval && currentEval.kriteria_kolom && currentEval.kriteria_kolom.length > 0">
                                            <template x-for="(sub, sIdx) in currentEval.kriteria_kolom" :key="sIdx">
                                                <td class="px-1 py-1 border-r border-gray-100 text-center">
                                                    <input type="text" 
                                                           :name="`nilais[${index}][nilai_data][${sIdx}]`" 
                                                           x-model="siswa.nilai_data[sIdx]"
                                                           class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs text-center font-bold focus:ring-primer focus:border-primer"
                                                           placeholder="-">
                                                </td>
                                            </template>
                                        </template>
                                        <template x-if="currentEval && (!currentEval.kriteria_kolom || currentEval.kriteria_kolom.length === 0)">
                                            <td class="px-1 py-1 border-r border-gray-100 text-center">
                                                <input type="text" 
                                                       :name="`nilais[${index}][nilai_data][0]`" 
                                                       x-model="siswa.nilai_data[0]"
                                                       class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs text-center font-bold focus:ring-primer focus:border-primer"
                                                       placeholder="-">
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </form>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4 rounded-b-xl">
                    <!-- Pagination Navigation for Modal -->
                    <div class="flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-600 w-full sm:w-auto" x-show="totalPages > 1">
                        <div>
                            Halaman <span class="font-medium" x-text="modalCurrentPage"></span> dari <span class="font-medium" x-text="totalPages"></span>
                        </div>
                        <div class="flex gap-1">
                            <button @click="if(modalCurrentPage > 1) modalCurrentPage--" type="button" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50" :disabled="modalCurrentPage === 1">Sebelumnya</button>
                            <button @click="if(modalCurrentPage < totalPages) modalCurrentPage++" type="button" class="px-3 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50" :disabled="modalCurrentPage === totalPages">Selanjutnya</button>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 w-full sm:w-auto">
                        <button type="button" @click="showNilaiAkhirScoreModal = false" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="button" onclick="document.getElementById('nilaiAkhirForm').submit()" class="bg-primer hover:bg-primer/90 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Nilai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
