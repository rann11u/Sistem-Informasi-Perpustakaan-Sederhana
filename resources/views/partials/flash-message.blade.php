{{-- Notifikasi Toast Sukses --}}
@if (session('success'))
    <div id="toastSuccess" class="toast-notification toast-success" role="status">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <div style="flex:1;">
            <div style="font-weight:600; font-size:13px;">Berhasil!</div>
            <div style="font-size:12px; opacity:0.9;">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-toast-close" onclick="closeToast('toastSuccess')">&times;</button>
    </div>
@endif

{{-- Notifikasi Modal Pop Up saat Error Validasi / Error Input Server --}}
@if (session('error') || $errors->any())
    <div id="errorModalPopup" class="custom-modal-overlay" style="display: flex;">
        <div class="custom-modal-box error-popup-box">
            <div class="modal-icon-wrapper error-icon-bg">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>

            <h3 class="modal-title">Terjadi Kesalahan Input</h3>

            <div class="error-modal-content">
                @if (session('error'))
                    <p class="error-msg-single">{{ session('error') }}</p>
                @endif

                @if ($errors->any())
                    <ul class="error-modal-list">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="modal-actions" style="margin-top:20px;">
                <button type="button" class="btn-modal-confirm" style="width:100%;" onclick="closeErrorModal()">
                    Mengerti & Perbaiki
                </button>
            </div>
        </div>
    </div>

    <script>
        function closeErrorModal() {
            var modal = document.getElementById('errorModalPopup');
            if (modal) modal.style.display = 'none';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeErrorModal();
        });
    </script>
@endif

<script>
    function closeToast(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }
    // Auto hide toast setelah 4 detik
    setTimeout(function() {
        var el = document.getElementById('toastSuccess');
        if (el) el.style.opacity = '0';
        setTimeout(function() { if (el) el.style.display = 'none'; }, 300);
    }, 4000);
</script>
