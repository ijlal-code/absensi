<div id="single-employee-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeEmployeeModal()"></div>

    <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-2xl transition-all sm:my-4 sm:w-full sm:max-w-6xl border-t-8 border-primary-600">
            
            {{-- Modal Header --}}
            <div class="bg-white px-6 py-4 border-b flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h3 class="text-2xl font-bold leading-6 text-gray-900">Kartu Data Karyawan</h3>
                    <p class="text-sm text-gray-500 mt-1" id="modal-header-sub">Nama - NIK</p>
                </div>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeEmployeeModal()">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="px-6 py-6 bg-gray-50 h-[80vh] overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- KIRI: FOTO --}}
                    <div class="lg:col-span-3 flex flex-col gap-4">
                        <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                            <div class="text-center mb-2"><span class="text-[10px] font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded uppercase">Foto Terbaru</span></div>
                            <div class="aspect-[3/4] w-full bg-gray-100 rounded overflow-hidden flex items-center justify-center border border-gray-300">
                                <img id="img-foto-baru" src="" class="object-cover w-full h-full hidden">
                                <span id="no-foto-baru" class="text-xs text-gray-400">Tidak ada foto</span>
                            </div>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow border border-gray-200">
                            <div class="text-center mb-2"><span class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded uppercase">Foto Badge</span></div>
                            <div class="aspect-[3/4] w-full bg-gray-100 rounded overflow-hidden flex items-center justify-center border border-gray-300">
                                <img id="img-foto-lama" src="" class="object-cover w-full h-full hidden">
                                <span id="no-foto-lama" class="text-xs text-gray-400">Tidak ada foto</span>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: DATA KARYAWAN --}}
                    <div class="lg:col-span-9">
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <div class="px-6 py-3 bg-primary-50 border-b border-primary-100 flex items-center">
                                <h4 class="text-lg font-bold text-gray-800">Data Lengkap Karyawan</h4>
                            </div>
                            
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm">
                                    
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">SAP / NIK</p><p class="font-mono font-bold text-gray-900"><span id="d-sap">-</span> / <span id="d-nik">-</span></p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Nama Karyawan</p><p class="font-bold text-gray-900 text-base" id="d-nama">-</p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Employee Subgroup</p><p class="font-semibold text-gray-800" id="d-subgroup">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Position / Band</p><p class="font-semibold text-gray-800"><span id="d-position">-</span> (<span id="d-band">-</span>)</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_DIR</p><p class="font-semibold text-gray-800" id="d-txt-dir">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_DEPT</p><p class="font-semibold text-gray-800" id="d-txt-dept">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_BIRO</p><p class="font-semibold text-gray-800" id="d-txt-biro">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">TXT_SECT</p><p class="font-semibold text-gray-800" id="d-txt-sect">-</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Organizational Unit</p><p class="font-semibold text-gray-800" id="d-org-unit">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Cost Center (Text)</p><p class="font-semibold text-gray-800" id="d-cost-txt">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Cost Ctr (Code)</p><p class="font-mono font-semibold text-gray-800" id="d-cost-ctr">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Personnel Area</p><p class="font-semibold text-gray-800" id="d-pers-area">-</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Obj Dept / Obj Biro</p><p class="font-mono text-gray-800 text-xs"><span id="d-obj-dept">-</span> / <span id="d-obj-biro">-</span></p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Obj Sect / Obj Grp</p><p class="font-mono text-gray-800 text-xs"><span id="d-obj-sect">-</span> / <span id="d-obj-grp">-</span></p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Abrev. Position</p><p class="font-semibold text-gray-800" id="d-abrev-pos">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Abrev. Organization</p><p class="font-semibold text-gray-800" id="d-abrev-org">-</p></div>

                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Birth Date / Umur</p><p class="font-semibold text-gray-800"><span id="d-birth">-</span> (<span id="d-umur">-</span> Thn)</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Tempat Lahir</p><p class="font-semibold text-gray-800" id="d-tmplahir">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Gender Key</p><p class="font-semibold text-gray-800" id="d-gender">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Religious</p><p class="font-semibold text-gray-800" id="d-religion">-</p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Date Terminasi</p><p class="font-bold text-red-600" id="d-terminasi">-</p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Organilk / s.d</p><p class="font-bold text-green-700"><span id="d-organilk">-</span> / <span id="d-sd">-</span></p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">Masa Kerja</p><p class="font-semibold text-gray-800"><span id="d-masa">-</span> Tahun</p></div>
                                    <div class="group border-b border-gray-50 pb-1"><p class="text-xs text-gray-400 mb-0.5">E-mail</p><p class="font-semibold text-blue-600 break-all" id="d-email">-</p></div>
                                    
                                    <div class="group border-b border-gray-50 pb-1 md:col-span-2"><p class="text-xs text-gray-400 mb-0.5">Pendidikan</p><p class="font-semibold text-gray-800" id="d-pendidikan">-</p></div>
                                    <div class="group border-b border-gray-50 pb-1 md:col-span-2"><p class="text-xs text-gray-400 mb-0.5">Alamat</p><p class="font-medium text-gray-800 bg-gray-50 p-2 rounded block w-full text-xs" id="d-alamat">-</p></div>

                                    {{-- KONTAK DINAMIS DI MODAL --}}
                                    <div class="group border-b border-gray-50 pb-2 md:col-span-2">
                                        <p class="text-xs text-gray-400 mb-1">Kontak Telepon / HP</p>
                                        <div class="flex flex-wrap gap-8 bg-gray-50 p-3 rounded border border-gray-100">
                                            
                                            {{-- HP 1 Container --}}
                                            <div id="container-hp1">
                                                <span id="label-hp1" class="text-[10px] text-gray-400 uppercase tracking-wider block">No HP 1</span>
                                                <span id="d-hp1" class="font-semibold text-gray-600 text-sm">-</span>
                                            </div>

                                            {{-- HP 2 Container --}}
                                            <div id="container-hp2">
                                                <span id="label-hp2" class="text-[10px] text-gray-400 uppercase tracking-wider block">No HP 2</span>
                                                <span id="d-hp2" class="font-semibold text-gray-600 text-sm">-</span>
                                            </div>

                                            {{-- HP 3 Container --}}
                                            <div id="container-hp3">
                                                <span id="label-hp3" class="text-[10px] text-gray-400 uppercase tracking-wider block">No HP 3</span>
                                                <span id="d-hp3" class="font-semibold text-gray-600 text-sm">-</span>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t flex justify-end">
                <button type="button" class="bg-white py-2 px-6 border border-gray-300 rounded-md shadow-sm text-sm font-bold text-gray-700 hover:bg-gray-100 focus:outline-none" onclick="closeEmployeeModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('single-employee-modal');
    
    const setText = (id, value) => {
        const el = document.getElementById(id);
        if(el) {
            el.textContent = (value && value.toString().trim() !== "") ? value : '-';
        }
    };

    const formatPhoneNumber = (str) => {
        if (!str) return '-';
        return str.toString().replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
    };

    function showEmployeeModal(btn) {
        const data = JSON.parse(btn.getAttribute('data-json'));
        
        document.getElementById('modal-header-sub').textContent = `${data.nama || '-'} - ${data.nik || '-'}`;

        const handleImage = (imgId, noImgId, src) => {
            const imgEl = document.getElementById(imgId);
            const noImgEl = document.getElementById(noImgId);
            if(src && src !== 'null' && src !== "") {
                imgEl.src = src;
                imgEl.classList.remove('hidden');
                noImgEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                noImgEl.classList.remove('hidden');
            }
        };
        
        handleImage('img-foto-baru', 'no-foto-baru', data.foto_baru);
        handleImage('img-foto-lama', 'no-foto-lama', data.foto_lama);

        setText('d-sap', data.sap);
        setText('d-nik', data.nik);
        setText('d-nama', data.nama);
        
        // --- LOGIKA DINAMIS NOMOR HP ---
        const primaryKey = data.primary_phone_key; // no_hp_1, no_hp_2, atau no_hp_3

        // Isi Data Nomornya dulu
        setText('d-hp1', formatPhoneNumber(data.hp1));
        setText('d-hp2', formatPhoneNumber(data.hp2));
        setText('d-hp3', formatPhoneNumber(data.hp3));

        // Fungsi Helper untuk set style
        const setPhoneStyle = (suffixId, isPrimary, defaultLabel) => {
            const labelEl = document.getElementById('label-' + suffixId);
            const valEl = document.getElementById('d-' + suffixId);

            if (isPrimary) {
                // Style jika Utama (Teks Hijau Tebal & Label "Nomor Utama")
                labelEl.textContent = "Nomor Utama";
                labelEl.className = "text-[10px] text-green-600 font-bold uppercase tracking-wider block";
                valEl.className = "font-bold text-green-700 text-base";
            } else {
                // Style Standar (Abu-abu & Label Default HP 1/2/3)
                labelEl.textContent = defaultLabel;
                labelEl.className = "text-[10px] text-gray-400 uppercase tracking-wider block";
                valEl.className = "font-semibold text-gray-600 text-sm";
            }
        };

        // Terapkan style berdasarkan primaryKey
        setPhoneStyle('hp1', primaryKey === 'no_hp_1', 'No HP 1');
        setPhoneStyle('hp2', primaryKey === 'no_hp_2', 'No HP 2');
        setPhoneStyle('hp3', primaryKey === 'no_hp_3', 'No HP 3');
        // ------------------------------

        setText('d-subgroup', data.subgroup);
        setText('d-position', data.position);
        setText('d-band', data.band);
        setText('d-txt-dir', data.txt_dir);
        setText('d-txt-dept', data.txt_dept);
        setText('d-txt-biro', data.txt_biro);
        setText('d-txt-sect', data.txt_sect);
        setText('d-org-unit', data.org_unit);
        setText('d-cost-txt', data.cost_center_text);
        setText('d-cost-ctr', data.cost_ctr);
        setText('d-pers-area', data.pers_area);
        setText('d-obj-dept', data.obj_dept);
        setText('d-obj-biro', data.obj_biro);
        setText('d-obj-sect', data.obj_sect);
        setText('d-obj-grp', data.obj_grp);
        setText('d-abrev-pos', data.abrev_pos);
        setText('d-abrev-org', data.abrev_org);
        setText('d-birth', data.birth_date);
        setText('d-umur', data.umur);
        setText('d-tmplahir', data.tempat_lahir);
        setText('d-gender', data.gender);
        setText('d-religion', data.religious);
        setText('d-terminasi', data.terminasi);
        setText('d-organilk', data.organilk);
        setText('d-sd', data.sd);
        setText('d-masa', data.masa_kerja);
        setText('d-email', data.email);
        setText('d-pendidikan', data.pendidikan);
        setText('d-alamat', data.alamat);

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEmployeeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>