<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
@media print {
    body * {
        visibility: hidden; /* hide everything */
    }
    table, table * , .print {
        visibility: visible; /* show table and elements with class "print" */
    }
    table {
        position: absolute;
        left: 0;
        top: 40px; /* push it down a bit so the heading shows above */
        width: 100%; /* full width when printing */
    }
    .print {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        text-align: absolute;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .no-print {
        display: none !important;
    }
    




}
</style>
<body class="bg-light">
    <h1 class="text-center mt-5">Products Overview</h1>
    



    <div class="container mt-5">
        <div class="row mb-4 text-center">
    <!-- Card 1: Overview -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-2"><i class="bi bi-speedometer2"></i></h5>
                <h6 class="text-muted">Overview</h6>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Products -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-2"><i class="bi bi-box-seam"></i></h5>
                <h1 class="display-6 mb-0 text-dark">
                    <?= isset($totalCount) ? $totalCount : (isset($products) ? count($products) : 0) ?>
                </h1>
                <span class="text-muted">Total Products</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Status -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-2"><i class="bi bi-graph-up"></i></h5>
                <p class="mb-0 fs-6">
                    <?php
                        $count = isset($totalCount) ? $totalCount : (isset($products) ? count($products) : 0);
                        
                        if ($count === 0) {
                            echo "No products yet.";
                        } elseif ($count < 5) {
                            echo "Few products <i class='bi bi-graph-down'></i>";
                        } elseif ($count < 20) {
                            echo "Growing list.";
                        } else {
                            echo "Large inventory!";
                        }
                    ?>
                </p>
                <span class="text-muted">Status</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Products Created Today -->
    <div class="col-md-2">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-2"><i class="bi bi-calendar-plus"></i></h5>
                <h1 class="display-6 mb-0 text-dark">
                    <?php
                        $today = date('Y-m-d');
                        $createdToday = 0;
                        
                        // Get all products created today from the database, not just current page
                        try {
                            $productModel = new \App\Models\ProductModel();
                            $todaysProducts = $productModel->where('DATE(created_at)', $today)->findAll();
                            $createdToday = count($todaysProducts);
                        } catch (Exception $e) {
                            // Fallback to current page products if database query fails
                            if (!empty($products)) {
                                foreach ($products as $p) {
                                    if (date('Y-m-d', strtotime($p['created_at'])) === $today) {
                                        $createdToday++;
                                    }
                                }
                            }
                        }
                        echo $createdToday;
                    ?>
                </h1>
                <span class="text-muted">Created Today</span>
            </div>
        </div>
    </div>

    
</div>

        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-md-8 ">
                <form method="GET" action="<?= base_url('/') ?>" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-dark w-75" 
                               placeholder="Search products by name or description..." 
                               value="<?= isset($keyword) ? esc($keyword) : '' ?>">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <?php if (isset($keyword) && !empty($keyword)): ?>
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle"></i> Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <?php if (isset($keyword) && !empty($keyword)): ?>
                    <div class="alert alert-info mb-0 py-2">
                        <small><i class="bi bi-info-circle"></i> Found <?= $totalCount ?> result(s) for "<?= esc($keyword) ?>"</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="print">Product List</h2>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addProductModal">
            + Add Product
        </button>
        <button class="btn btn-outline-dark" onclick="window.print()"><i class="bi bi-printer"></i> Print Table</button>
    </div>
</div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-light text-dark">
                        <h5 class="modal-title" id="addProductModalLabel"><i class="bi bi-plus"></i> Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('products/store') ?>" method="post">
                            <div class="mb-3">  
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" id="product_name" name="product_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" id="price" name="price" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-dark">Save Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover align-middle">
          
            <thead class="table">
                <tr>
                    
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Date Created</th>
                    <th>Date Updated</th>
                    <th width="180" class="no-print">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            
                            <td><?= $p['product_name'] ?></td>
                            <td><?= $p['description'] ?></td>
                            <td><?= $p['price'] ?></td>
                            <td><?= date('F d, Y', strtotime($p['created_at'])) ?></td>
<td><?= date('F d, Y', strtotime($p['updated_at'])) ?></td>

                            <td>
                                <!-- Edit Button -->
                                <button type="button" class="btn btn-outline-secondary btn-sm no-print" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $p['id'] ?>"><i class="bi bi-pencil"></i> Edit</button>
                                <!-- Delete Button -->
                                <button type="button" class="btn btn-outline-danger btn-sm no-print" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?= $p['id'] ?>"><i class="bi bi-trash"></i> Delete</button>
                            </td>
                        </tr>
                        <!-- Edit Product Modal -->
                        <div class="modal fade" id="editProductModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="editProductModalLabel<?= $p['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-secondary text-dark">
                                        <h5 class="modal-title text-light id=" editProductModalLabel<?= $p['id'] ?>">Edit Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?= base_url('products/update/' . $p['id']) ?>" method="post">
                                            <div class="mb-3">
                                                <label for="edit_product_name<?= $p['id'] ?>" class="form-label">Product Name</label>
                                                <input type="text" id="edit_product_name<?= $p['id'] ?>" name="product_name" class="form-control" value="<?= $p['product_name'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_description<?= $p['id'] ?>" class="form-label">Description</label>
                                                <textarea id="edit_description<?= $p['id'] ?>" name="description" class="form-control" rows="3"><?= $p['description'] ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_price<?= $p['id'] ?>" class="form-label">Price</label>
                                                <input type="number" step="0.01" id="edit_price<?= $p['id'] ?>" name="price" class="form-control" value="<?= $p['price'] ?>" required>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-outline-dark">Update Product</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Product Modal -->
                        <div class="modal fade" id="deleteProductModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="deleteProductModalLabel<?= $p['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteProductModalLabel<?= $p['id'] ?>">Delete Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete <strong><?= $p['product_name'] ?></strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="<?= base_url('products/delete/' . $p['id']) ?>" method="post">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Bootstrap Pagination -->
        <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
            <div class="d-flex justify-content-center mt-4">
                <?= $pager->links('default', 'bootstrap_pagination') ?>
            </div>
        <?php endif; ?>
        
        <!-- Pagination Info -->
        <?php if (isset($pager)) : ?>
            <div class="d-flex justify-content-center mt-2">
                <small class="text-muted">
                    <?php
                        $details = $pager->getDetails();
                        $currentPage = $details['currentPage'];
                        $perPage = $details['perPage'];
                        $total = $details['total'];
                        $firstItem = (($currentPage - 1) * $perPage) + 1;
                        $lastItem = min($currentPage * $perPage, $total);
                    ?>
                    Showing <?= $firstItem ?> to <?= $lastItem ?> of <?= $total ?> products
                </small>
            </div>
        <?php endif; ?>
    </div>
    <?php if (session()->getFlashdata('message')): ?>
        <script>
            window.alert("<?= session()->getFlashdata('message') ?>");
        </script>
    <?php endif; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Search Enhancement Script -->
    <script>
        // Focus on search input when page loads if there's a search term
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="keyword"]');
            const urlParams = new URLSearchParams(window.location.search);
            const keyword = urlParams.get('keyword');
            
            // Auto-focus search input if there's no search term
            if (!keyword && searchInput) {
                searchInput.focus();
            }
            
            // Add search on Enter key
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.closest('form').submit();
                    }
                });
            }
        });
        
        // Add loading state to search button
        document.querySelector('form').addEventListener('submit', function() {
            const searchBtn = this.querySelector('button[type="submit"]');
            const originalText = searchBtn.innerHTML;
            searchBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Searching...';
            searchBtn.disabled = true;
            
            // Re-enable after a short delay (in case of errors)
            setTimeout(() => {
                searchBtn.innerHTML = originalText;
                searchBtn.disabled = false;
            }, 3000);
        });
    </script>
</body>

</html>