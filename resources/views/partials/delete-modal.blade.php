<!-- Modal Konfirmasi Hapus Kustom (Tengah Layar & Modern) -->
<div id="deleteModal" class="custom-modal-overlay" style="display: none;" onclick="handleModalBackdrop(event)">
    <div class="custom-modal-box">
        <div class="modal-icon-wrapper">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p id="deleteModalMessage" class="modal-message">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn-modal-confirm" id="confirmDeleteBtn" onclick="submitDeleteForm()">Ya, Hapus</button>
        </div>
    </div>
</div>

<style>
    .custom-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 16px;
    }

    .custom-modal-box {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        padding: 26px 24px;
        text-align: center;
        animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.92) translateY(-8px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-icon-wrapper {
        width: 54px;
        height: 54px;
        background-color: #fee2e2;
        border: 4px solid #fef2f2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .modal-title {
        margin: 0 0 8px;
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }

    .modal-message {
        margin: 0 0 22px;
        font-size: 14px;
        color: #4b5563;
        line-height: 1.5;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .btn-modal-cancel {
        flex: 1;
        padding: 10px 16px;
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .btn-modal-cancel:hover {
        background-color: #e5e7eb;
    }

    .btn-modal-confirm {
        flex: 1;
        padding: 10px 16px;
        background-color: #dc2626;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }

    .btn-modal-confirm:hover {
        background-color: #b91c1c;
    }
</style>

<script>
    let activeDeleteForm = null;

    function openDeleteModal(event, itemName) {
        if (event) {
            event.preventDefault();
            activeDeleteForm = event.target.tagName === 'FORM' ? event.target : event.target.closest('form');
        }
        
        const messageEl = document.getElementById('deleteModalMessage');
        if (itemName) {
            messageEl.innerHTML = 'Apakah Anda yakin ingin menghapus <strong>' + escapeHtml(itemName) + '</strong>?<br><span style="font-size: 12px; color: #6b7280; display: inline-block; margin-top: 4px;">Data yang dihapus tidak dapat dikembalikan.</span>';
        } else {
            messageEl.innerHTML = 'Apakah Anda yakin ingin menghapus data ini?<br><span style="font-size: 12px; color: #6b7280; display: inline-block; margin-top: 4px;">Data yang dihapus tidak dapat dikembalikan.</span>';
        }

        const modal = document.getElementById('deleteModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
        activeDeleteForm = null;
    }

    function handleModalBackdrop(event) {
        if (event.target.id === 'deleteModal') {
            closeDeleteModal();
        }
    }

    function submitDeleteForm() {
        if (activeDeleteForm) {
            activeDeleteForm.submit();
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('deleteModal');
            if (modal && modal.style.display === 'flex') {
                closeDeleteModal();
            }
        }
    });
</script>

