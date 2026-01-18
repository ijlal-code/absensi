{{-- MODAL STRUCTURE --}}
{{-- Hidden by default using class 'hidden' --}}
<div id="employeeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        {{-- Background Overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

        {{-- Centering Trick --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Panel --}}
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
            
            {{-- Header Modal --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b">
                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                    Detail Karyawan
                </h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Body Modal (Konten Dinamis akan dimuat di sini) --}}
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div id="modalContent">
                    {{-- Default Loading State --}}
                    <div class="flex flex-col items-center justify-center py-10">
                        <i class="fas fa-circle-notch fa-spin text-4xl text-blue-500 mb-3"></i>
                        <p class="text-gray-500">Memuat data...</p>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        const modal = document.getElementById('employeeModal');
        modal.classList.add('hidden');
        
        // Optional: Reset content to loading state after close
        setTimeout(() => {
            document.getElementById('modalContent').innerHTML = `
                <div class="flex flex-col items-center justify-center py-10">
                    <i class="fas fa-circle-notch fa-spin text-4xl text-blue-500 mb-3"></i>
                    <p class="text-gray-500">Memuat data...</p>
                </div>
            `;
        }, 300);
    }

    // Close modal on Esc key press
    document.addEventListener('keydown', function(event) {
        if(event.key === "Escape") {
            closeModal();
        }
    });
</script>