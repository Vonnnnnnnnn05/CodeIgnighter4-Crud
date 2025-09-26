<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    body{
        font-family: monospace;
    }
    /* Sidebar style */
    .sidebar {
        width: 150px;
        min-height: 100vh;
        transition: all 0.3s ease-in-out;
        background-color: #fff;
        border-right: 1px solid #ddd;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .main-content {
        margin-left: 150px;
        transition: all 0.3s ease-in-out;
        position: relative;
    }

    /* Toggle button (outside sidebar) - fixed so it's always visible while scrolling */
    .toggle-btn {
        position: fixed;
        top: 20px;
        z-index: 2001;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: left 0.25s ease-in-out, transform 0.25s ease-in-out;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        background-color: #fff;
        /* place it outside the sidebar by default (sidebar width 150px + gap 18px) */
        left: 170px;
    }

    /* Collapsed sidebar */
    .sidebar.collapsed {
        margin-left: -250px;
    }

    .main-content.expanded {
        margin-left: 0;
    }

    /* When sidebar is collapsed, move the toggle button to the left edge and flip icon */
    .sidebar.collapsed + .main-content .toggle-btn {
        left: 12px; /* visible at the left edge */
    }
    .sidebar.collapsed + .main-content .toggle-btn i {
        transform: rotate(180deg);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            height: 100vh;
        }

        .main-content {
            margin-left: 0;
        }
    }

    .nav-link.d-flex.align-items-center {
        font-size: 0.8rem;
        /* smaller text */
        font-weight: 500;
        /* optional: make it a bit bolder */
        color: #333;
        /* optional: change text color if needed */
    }

    .nav-link.d-flex.align-items-center:hover {
        background-color: #f8f9fa;
        color: #000;
        text-decoration: none;
    }
    /* make logout appear clickable */
    .logout-pointer {
        cursor: pointer;
    }
    
    
</style>
<body>
    
<body class="bg-light">
   
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar bg-white border-end">
            <div class="sidebar-header d-flex align-items-center px-3 py-3 border-bottom">
                <h5 class="mb-0"><i class="bi bi-box"></i> Product Management</h5>
            </div>
            <ul class="nav flex-column px-2 mt-3">
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="<?= base_url('/dashboard') ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Product Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="<?= base_url('/product_card') ?>">
                        <i class="bi bi-upc-scan me-2"></i> Products with Barcode
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link d-flex align-items-center" href="<?= base_url('/reports') ?>">
                        <i class="bi bi-bar-chart-line me-2"></i> Reports
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="<?= base_url('/logout') ?>" class="nav-link text-danger d-flex align-items-center logout-pointer logout-link">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>

            </ul>
        </nav>
        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <!-- Toggle button OUTSIDE the sidebar -->
            <button id="toggleSidebar" class="btn btn-outline-secondary toggle-btn">
                <i class="bi bi-file"></i>
            </button>
            <div class="d-flex">

            </div>
            <h2 class="text-center mb-3">Products Card With Barcode</h2>

            <!-- Product cards -->
            <div class="container mt-4 text-center">
                <div class="row">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title mb-2"><?= esc($p['product_name']) ?></h5>
                                        <p class="card-text mb-3 text-muted" style="flex:0 0 auto;">
                                            <?= esc($p['description']) ?: '<span class="text-secondary">No description</span>' ?>
                                        </p>
                                        <p class="card-text mb-3 text-muted" style="flex:0 0 auto;">
                                            <?= esc($p['price']) ?: '<span class="text-secondary">No price</span>' ?>
                                        </p>
                                        <div class="mt-auto">
                                            <svg id="pc-barcode-<?= $p['id'] ?>"></svg>
                                            <!-- View button (does not affect other behaviors) -->
                                            <div class="mt-3 text-center">
                                                <button type="button" class="btn btn-sm btn-outline-dark view-btn" data-id="<?= esc($p['id']) ?>" data-name="<?= esc($p['product_name']) ?>" data-description="<?= esc($p['description']) ?>" data-created="<?= date('M d, Y', strtotime($p['created_at'])) ?>" data-price="<?= esc($p['price']) ?>"><i class="bi bi-search"></i> View</button>
                                            </div>
                                            <div class="small text-muted mt-2">Created: <?= date('M d, Y', strtotime($p['created_at'])) ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-4">
                            <p class="text-muted">No products found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>



     <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.querySelector(".main-content");
            const toggleBtn = document.getElementById("toggleSidebar");

            toggleBtn.addEventListener("click", () => {
                const isCollapsed = sidebar.classList.toggle("collapsed");
                mainContent.classList.toggle("expanded");
                // swap icon
                const icon = toggleBtn.querySelector('i');
                if (icon) icon.className = isCollapsed ? 'bi bi-chevron-right' : 'bi bi-file';
                // aria
                toggleBtn.setAttribute('aria-expanded', String(!isCollapsed));
                // persist
                try { localStorage.setItem('si_crud_sidebar_collapsed', String(isCollapsed)); } catch (e) {}
            });

            // Restore state on load
            try {
                const collapsed = localStorage.getItem('si_crud_sidebar_collapsed') === 'true';
                if (collapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    const icon = toggleBtn.querySelector('i');
                    if (icon) icon.className = 'bi bi-chevron-right';
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            } catch (e) {}
        });
    </script>
</script>
    <!-- JsBarcode CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    try {
                        // card barcode
                        JsBarcode('#pc-barcode-<?= $p['id'] ?>', '<?= esc($p['id']) ?>', {format: 'CODE128', width:2, height:40, displayValue: true});
                        // modal barcode (slightly taller for visibility)
                        try { JsBarcode('#pc-modal-barcode-<?= $p['id'] ?>', '<?= esc($p['id']) ?>', {format: 'CODE128', width:2, height:60, displayValue: true}); } catch (e) {}
                    } catch (e) {}
                <?php endforeach; ?>
            <?php endif; ?>
        });
    </script>

    <!-- Use Bootstrap modal for smooth open/close (include bundle for modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Shared Bootstrap modal (single instance) -->
    <div class="modal fade" id="pcSharedModal" tabindex="-1" aria-labelledby="pcSharedModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="pcSharedModalLabel">Product details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
                    <div class="modal-body text-center">
            <p><strong>Name:</strong> <span id="pc-modal-name"></span></p>
            <p><strong>Description:</strong> <span id="pc-modal-description"></span></p>
            <p><strong>price:</strong> <span id="pc-modal-price"></span></p>
            <p><strong>Created:</strong> <span id="pc-modal-created"></span></p>
            <p><strong>ID:</strong> <span id="pc-modal-id"></span></p>
            <div class="text-center mt-3">
                <svg id="pc-shared-modal-barcode" aria-hidden="true"></svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
        // Fill and show Bootstrap modal on view button click, generate barcode on show
        (function(){
            var pcSharedModalEl = document.getElementById('pcSharedModal');
            var bsModal = new bootstrap.Modal(pcSharedModalEl, { backdrop: true });

            document.addEventListener('click', function(e){
                var t = e.target.closest('.view-btn');
                if (!t) return;
                var id = t.getAttribute('data-id');
                var name = t.getAttribute('data-name') || '';
                var desc = t.getAttribute('data-description') || '';
                var created = t.getAttribute('data-created') || '';
                var price = t.getAttribute('data-price') || '';

                document.getElementById('pc-modal-name').textContent = name;
                document.getElementById('pc-modal-description').textContent = desc || 'No description';
                document.getElementById('pc-modal-created').textContent = created;
                document.getElementById('pc-modal-id').textContent = id;
                document.getElementById('pc-modal-price').textContent = price || 'No price';

                // generate barcode into shared modal svg (try/catch so it won't break)
                try {
                    JsBarcode('#pc-shared-modal-barcode', String(id), {format: 'CODE128', width:2, height:60, displayValue: true});
                } catch (e) { }

                bsModal.show();
            }, false);
        })();
    </script>
    
    <script>
        // Logout confirmation using SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.logout-link').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    Swal.fire({
                        title: 'Are you sure you want to logout?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, logout',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show a short success toast then redirect to logout with ?silent=1
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Successfully logged out',
                                showConfirmButton: false,
                                timer: 1400,
                                timerProgressBar: true,
                            }).then(() => {
                                // append silent param to avoid server flash duplication
                                const url = new URL(href, window.location.origin);
                                url.searchParams.set('silent', '1');
                                window.location.href = url.toString();
                            });
                        }
                    });
                });
            });
        });
    </script>
    
</body>
</html>