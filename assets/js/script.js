/**
 * ============================================================================
 * VANILLA JAVASCRIPT — LogiCargo Enterprise Dashboard
 * ============================================================================
 * 
 * Job 4 (Controller / Driver Specialist):
 * Client-side interactions for the LogiCargo Dashboard:
 * 
 *   1. Sidebar Toggle & Mobile Offcanvas Menu
 *   2. Dynamic Form Input Switcher (Jenis Kargo)
 *   3. Table Search & Real-time Client-side Filter
 *   4. Dashboard Counter Animation for KPI Metrics
 *   5. Toast Notifications
 *   6. Alert Banner Auto-dismiss
 *   7. Delete Confirmation
 * 
 * @package  LogiCargo Dashboard
 * @version  1.0.0
 * ============================================================================
 */


/* ══════════════════════════════════════════════════════════════════════════════
   1. SIDEBAR TOGGLE & MOBILE OFFCANVAS MENU
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * openSidebar() — Membuka sidebar drawer di mobile
 * Menambahkan class 'open' ke sidebar dan overlay
 */
function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar) {
        sidebar.classList.add('open');
    }
    if (overlay) {
        overlay.classList.add('active');
    }
    // Prevent body scrolling when sidebar is open
    document.body.style.overflow = 'hidden';
}

/**
 * closeSidebar() — Menutup sidebar drawer di mobile
 * Menghapus class 'open' dari sidebar dan overlay
 */
function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar) {
        sidebar.classList.remove('open');
    }
    if (overlay) {
        overlay.classList.remove('active');
    }
    // Restore body scrolling
    document.body.style.overflow = '';
}

// Close sidebar when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSidebar();
    }
});


/* ══════════════════════════════════════════════════════════════════════════════
   2. DYNAMIC FORM INPUT SWITCHER — Jenis Kargo
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * toggleDynamicFields() — Menampilkan/menyembunyikan field spesifik
 * berdasarkan pilihan "Jenis Kargo" pada form reservasi.
 * 
 * Implementasi tanpa reload halaman — pure DOM manipulation.
 * 
 * Mapping:
 *   - 'reguler' → fields-reguler (jenis_paket, estimasi_hari)
 *   - 'kimia'   → fields-kimia (tingkat_bahaya, jenis_sertifikasi_sandi)
 *   - 'pecah'   → fields-pecah (ketebalan_bubble_wrap, biaya_asuransi_wajib)
 */
function toggleDynamicFields() {
    const selector = document.getElementById('select-jenis-kargo');
    if (!selector) return;

    const value = selector.value;

    // Get all dynamic field containers
    const fieldsReguler = document.getElementById('fields-reguler');
    const fieldsKimia   = document.getElementById('fields-kimia');
    const fieldsPecah   = document.getElementById('fields-pecah');

    // Close all dynamic fields first
    [fieldsReguler, fieldsKimia, fieldsPecah].forEach(function(el) {
        if (el) {
            el.classList.remove('open');
            // Disable inputs in hidden sections to prevent form validation conflicts
            const inputs = el.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                input.removeAttribute('required');
            });
        }
    });

    // Open the selected field container and enable its inputs
    let targetField = null;
    if (value === 'reguler' && fieldsReguler) {
        targetField = fieldsReguler;
    } else if (value === 'kimia' && fieldsKimia) {
        targetField = fieldsKimia;
    } else if (value === 'pecah' && fieldsPecah) {
        targetField = fieldsPecah;
    }

    if (targetField) {
        // Small delay to trigger CSS transition properly
        requestAnimationFrame(function() {
            targetField.classList.add('open');
            // Enable required validation on visible inputs
            const inputs = targetField.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                if (input.type !== 'hidden') {
                    input.setAttribute('required', 'required');
                }
            });
        });
    }
}

/**
 * resetDynamicFields() — Reset semua dynamic fields ke state tertutup
 * Dipanggil saat tombol "Reset" ditekan
 */
function resetDynamicFields() {
    const fields = document.querySelectorAll('.dynamic-fields');
    fields.forEach(function(el) {
        el.classList.remove('open');
        const inputs = el.querySelectorAll('input, select');
        inputs.forEach(function(input) {
            input.removeAttribute('required');
        });
    });
}


/* ══════════════════════════════════════════════════════════════════════════════
   3. TABLE SEARCH & REAL-TIME CLIENT-SIDE FILTER
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * applyTableFilters() — Filter tabel cargo berdasarkan:
 *   - Search text (pencarian di semua kolom)
 *   - Filter jenis kargo (dropdown)
 *   - Filter kota tujuan (dropdown)
 * 
 * Semua filter bekerja secara real-time tanpa reload halaman.
 */
function applyTableFilters() {
    const searchInput  = document.getElementById('table-search');
    const filterJenis  = document.getElementById('filter-jenis');
    const filterKota   = document.getElementById('filter-kota');
    const table        = document.getElementById('cargo-table');

    if (!table) return;

    const searchText = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const jenisValue = filterJenis ? filterJenis.value : '';
    const kotaValue  = filterKota  ? filterKota.value : '';

    const rows = table.querySelectorAll('tbody tr[data-row]');
    let visibleCount = 0;

    rows.forEach(function(row) {
        // Get filter data attributes from each row
        const rowText  = (row.getAttribute('data-search') || row.textContent).toLowerCase();
        const rowJenis = row.getAttribute('data-jenis') || '';
        const rowKota  = row.getAttribute('data-kota')  || '';

        // Apply all three filters (AND logic)
        const matchSearch = !searchText || rowText.includes(searchText);
        const matchJenis  = !jenisValue || rowJenis === jenisValue;
        const matchKota   = !kotaValue  || rowKota === kotaValue;

        if (matchSearch && matchJenis && matchKota) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update visible count display
    const countDisplay = document.getElementById('filter-count');
    if (countDisplay) {
        countDisplay.textContent = visibleCount + ' data ditampilkan';
    }
}

// Debounce function for search input to improve performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            func.apply(context, args);
        }, wait);
    };
}


/* ══════════════════════════════════════════════════════════════════════════════
   4. DASHBOARD COUNTER ANIMATION — KPI Metrics
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * animateCounters() — Animasi counter untuk KPI metrics di dashboard
 * 
 * Setiap elemen dengan class 'counter-animate' akan di-animasi
 * dari 0 ke nilai data-target.
 * 
 * Attributes:
 *   - data-target  : Nilai akhir (integer)
 *   - data-prefix  : Prefix text (e.g., "Rp ")
 *   - data-suffix  : Suffix text (e.g., " kg")
 *   - data-format  : Jika 'true', format angka dengan separator ribuan
 */
function animateCounters() {
    const counters = document.querySelectorAll('.counter-animate');

    counters.forEach(function(counter) {
        const target  = parseInt(counter.getAttribute('data-target')) || 0;
        const prefix  = counter.getAttribute('data-prefix') || '';
        const suffix  = counter.getAttribute('data-suffix') || '';
        const format  = counter.getAttribute('data-format') === 'true';
        const duration = 1500; // Animation duration in ms
        const startTime = performance.now();

        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function: ease-out cubic
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const currentValue = Math.round(easeOut * target);

            // Format number with thousand separators
            let displayValue = currentValue;
            if (format) {
                displayValue = currentValue.toLocaleString('id-ID');
            }

            counter.textContent = prefix + displayValue + suffix;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            }
        }

        requestAnimationFrame(updateCounter);
    });
}

/**
 * Intersection Observer — Trigger counter animation when cards scroll into view
 */
function initCounterObserver() {
    const counters = document.querySelectorAll('.counter-animate');
    if (counters.length === 0) return;

    // If IntersectionObserver is not supported, animate immediately
    if (!('IntersectionObserver' in window)) {
        animateCounters();
        return;
    }

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounters();
                observer.disconnect(); // Only animate once
            }
        });
    }, { threshold: 0.2 });

    // Observe the first counter's parent
    if (counters[0]) {
        const parent = counters[0].closest('.kpi-grid') || counters[0].parentElement;
        if (parent) observer.observe(parent);
    }
}


/* ══════════════════════════════════════════════════════════════════════════════
   5. TOAST NOTIFICATIONS
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * showToast() — Menampilkan toast notification
 * 
 * @param {string} message  Pesan yang ditampilkan
 * @param {string} type     Tipe toast: 'success', 'error', 'info'
 * @param {number} duration Durasi tampil dalam ms (default: 4000)
 */
function showToast(message, type, duration) {
    type = type || 'info';
    duration = duration || 4000;

    // Create or get toast container
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    // Icon mapping
    const icons = {
        success: 'bi-check-circle-fill',
        error:   'bi-exclamation-circle-fill',
        info:    'bi-info-circle-fill'
    };

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    toast.innerHTML = '<i class="bi ' + (icons[type] || icons.info) + '"></i>' +
                      '<span>' + message + '</span>';

    container.appendChild(toast);

    // Auto-remove after duration
    setTimeout(function() {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(24px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(function() {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, duration);
}


/* ══════════════════════════════════════════════════════════════════════════════
   6. ALERT BANNER AUTO-DISMISS
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * initAlertDismiss() — Auto-dismiss alert banners setelah 6 detik
 * dan enable manual close button
 */
function initAlertDismiss() {
    const alerts = document.querySelectorAll('.alert-banner');

    alerts.forEach(function(alert) {
        // Auto-dismiss after 6 seconds
        setTimeout(function() {
            dismissAlert(alert);
        }, 6000);

        // Manual close button
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                dismissAlert(alert);
            });
        }
    });
}

function dismissAlert(alert) {
    if (!alert) return;
    alert.style.opacity = '0';
    alert.style.transform = 'translateY(-10px)';
    alert.style.transition = 'all 0.3s ease';
    setTimeout(function() {
        if (alert.parentNode) {
            alert.style.display = 'none';
        }
    }, 300);
}


/* ══════════════════════════════════════════════════════════════════════════════
   7. DELETE CONFIRMATION
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * confirmDelete() — Konfirmasi hapus kargo dengan dialog
 * 
 * @param {string} id_resi ID Resi yang akan dihapus
 * @param {string} pengirim Nama pengirim (untuk display)
 */
function confirmDelete(id_resi, pengirim) {
    const confirmed = confirm(
        '⚠️ Konfirmasi Hapus Kargo\n\n' +
        'ID Resi: ' + id_resi + '\n' +
        'Pengirim: ' + pengirim + '\n\n' +
        'Data yang sudah dihapus tidak dapat dikembalikan.\nApakah Anda yakin ingin menghapus?'
    );

    if (confirmed) {
        // Create and submit a hidden form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.pathname;

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete';
        form.appendChild(actionInput);

        const resiInput = document.createElement('input');
        resiInput.type = 'hidden';
        resiInput.name = 'id_resi';
        resiInput.value = id_resi;
        form.appendChild(resiInput);

        document.body.appendChild(form);
        form.submit();
    }
}


/* ══════════════════════════════════════════════════════════════════════════════
   8. MODAL HELPERS
   ══════════════════════════════════════════════════════════════════════════════ */

/**
 * openModal() — Buka modal view detail
 * @param {string} modalId ID elemen modal
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * closeModal() — Tutup modal
 * @param {string} modalId ID elemen modal
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}


/* ══════════════════════════════════════════════════════════════════════════════
   9. INITIALIZATION — DOMContentLoaded
   ══════════════════════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize counter animations
    initCounterObserver();

    // Initialize alert auto-dismiss
    initAlertDismiss();

    // Attach search input listener with debouncing
    const searchInput = document.getElementById('table-search');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(applyTableFilters, 250));
    }

    // Attach filter select listeners
    const filterJenis = document.getElementById('filter-jenis');
    const filterKota  = document.getElementById('filter-kota');
    if (filterJenis) filterJenis.addEventListener('change', applyTableFilters);
    if (filterKota)  filterKota.addEventListener('change', applyTableFilters);

    // Initialize dynamic fields if form exists (check current selection)
    const jenisSelect = document.getElementById('select-jenis-kargo');
    if (jenisSelect && jenisSelect.value) {
        toggleDynamicFields();
    }

    // Close modals on backdrop click
    document.querySelectorAll('.modal-backdrop-custom').forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
});
