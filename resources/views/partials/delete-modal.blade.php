<!-- Modal Konfirmasi Kustom (Tengah Layar & Modern) -->
<div id="deleteModal" class="custom-modal-overlay" style="display: none;" onclick="handleModalBackdrop(event)">
    <div class="custom-modal-box">
        <div class="modal-icon-wrapper" id="confirmModalIconWrapper">
            <svg id="confirmModalIcon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>
        <h3 class="modal-title" id="confirmModalTitle">Konfirmasi Hapus</h3>
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

    .modal-icon-wrapper.variant-warning {
        background-color: #fef3c7;
        border-color: #fffbeb;
    }

    .modal-icon-wrapper.variant-success {
        background-color: #dcfce7;
        border-color: #f0fdf4;
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

    .btn-modal-confirm.variant-warning {
        background-color: #d97706;
    }

    .btn-modal-confirm.variant-warning:hover {
        background-color: #b45309;
    }

    .btn-modal-confirm.variant-success {
        background-color: #16a34a;
    }

    .btn-modal-confirm.variant-success:hover {
        background-color: #15803d;
    }

    .btn-modal-confirm:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>

<script>
    let activeDeleteForm = null;
    let activeConfirmText = 'Ya, Hapus';
    let activeLoadingText = 'Menghapus...';

    const MODAL_ICONS = {
        delete: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
        danger: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
        warning: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        success: '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
    };

    function openConfirmModal(event, options) {
        if (event) {
            event.preventDefault();
            activeDeleteForm = event.target.tagName === 'FORM' ? event.target : event.target.closest('form');
        }

        const opts = options || {};
        const variant = opts.variant || 'danger';
        const iconType = opts.icon || variant;
        const title = opts.title || 'Konfirmasi Tindakan';
        const messageHtml = opts.messageHtml || escapeHtml(opts.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?');
        const note = opts.note || '';
        activeConfirmText = opts.confirmText || 'Ya, Lanjutkan';
        activeLoadingText = opts.loadingText || 'Memproses...';

        const iconWrapper = document.getElementById('confirmModalIconWrapper');
        iconWrapper.className = 'modal-icon-wrapper' + (variant !== 'danger' ? ' variant-' + variant : '');
        iconWrapper.innerHTML = MODAL_ICONS[iconType] || MODAL_ICONS.danger;

        document.getElementById('confirmModalTitle').textContent = title;

        const messageEl = document.getElementById('deleteModalMessage');
        messageEl.innerHTML = messageHtml + (note
            ? '<br><span style="font-size: 12px; color: #6b7280; display: inline-block; margin-top: 6px;">' + escapeHtml(note) + '</span>'
            : '');

        const btn = document.getElementById('confirmDeleteBtn');
        btn.className = 'btn-modal-confirm' + (variant !== 'danger' ? ' variant-' + variant : '');
        btn.disabled = false;
        btn.textContent = activeConfirmText;

        const modal = document.getElementById('deleteModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function openDeleteModal(event, itemName) {
        const messageHtml = itemName
            ? 'Apakah Anda yakin ingin menghapus <strong>' + escapeHtml(itemName) + '</strong>?'
            : 'Apakah Anda yakin ingin menghapus data ini?';

        openConfirmModal(event, {
            title: 'Konfirmasi Hapus',
            messageHtml: messageHtml,
            note: 'Data yang dihapus tidak dapat dikembalikan.',
            confirmText: 'Ya, Hapus',
            loadingText: 'Menghapus...',
            variant: 'danger',
            icon: 'delete'
        });
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const btn = document.getElementById('confirmDeleteBtn');
        if (btn) {
            btn.disabled = false;
            btn.textContent = activeConfirmText;
        }
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
            const btn = document.getElementById('confirmDeleteBtn');
            if (btn) {
                btn.disabled = true;
                btn.textContent = activeLoadingText;
            }
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
