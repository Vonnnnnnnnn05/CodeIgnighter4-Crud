<?php
namespace App\Controllers;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    // Show all products with search and pagination
    public function index()
    {
        $productModel = new ProductModel();

        // Get search keyword from query string (?keyword=...)
        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            // Search by product_name or description with pagination
            $products = $productModel->like('product_name', $keyword)
                                     ->orLike('description', $keyword)
                                     ->paginate(5); // 5 items per page
            
            // Get total count for search results
            $totalCount = $productModel->like('product_name', $keyword)
                                      ->orLike('description', $keyword)
                                      ->countAllResults();
        } else {
            $products = $productModel->paginate(5); // 5 items per page
            
            // Get total count of all products
            $totalCount = $productModel->countAll();
        }

        $data = [
            'products' => $products,
            'pager'    => $productModel->pager,
            'totalCount' => $totalCount,
            'keyword'  => $keyword
        ];

        return view('product_view', $data);
    }

    // Show create form

    // Save product to DB
    public function store()
    {
        $productModel = new ProductModel();

        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'product_name' => [
                'label' => 'Product Name',
                'rules' => 'required|min_length[3]|max_length[100]|regex_match[/^[A-Za-z0-9\s\-_]+$/]',
                'errors' => [
                    'required' => 'Product name is required',
                    'min_length' => 'Product name must be at least 3 characters',
                    'max_length' => 'Product name cannot exceed 100 characters',
                    'regex_match' => 'Product name can only contain letters, numbers, spaces, hyphens, and underscores'
                ]
            ],
            'description' => [
                'label' => 'Description',
                'rules' => 'permit_empty|min_length[10]|max_length[500]',
                'errors' => [
                    'min_length' => 'Description must be at least 10 characters if provided',
                    'max_length' => 'Description cannot exceed 500 characters'
                ]
            ],
            'price' => [
                'label' => 'Price',
                'rules' => 'required|decimal|greater_than[0]|less_than_equal_to[999999.99]',
                'errors' => [
                    'required' => 'Price is required',
                    'decimal' => 'Price must be a valid decimal number',
                    'greater_than' => 'Price must be greater than 0',
                    'less_than_equal_to' => 'Price cannot exceed 999,999.99'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $errorMessage = implode('<br>', $errors);
            session()->setFlashdata('error', $errorMessage);
            return redirect()->back()->withInput();
        }

        $data = [
            'product_name' => trim($this->request->getPost('product_name')),
            'description'  => trim($this->request->getPost('description')),
            'price'        => $this->request->getPost('price'),
        ];

        if ($productModel->insert($data)) {
            session()->setFlashdata('message', 'Product added successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to add product. Please try again.');
        }

        return redirect()->to(base_url('/dashboard'));
    }

    // Update product
    public function update($id)
    {
        $productModel = new ProductModel();

        // Validation rules (same as store method)
        $validation = \Config\Services::validation();
        $validation->setRules([
            'product_name' => [
                'label' => 'Product Name',
                'rules' => 'required|min_length[3]|max_length[100]|regex_match[/^[A-Za-z0-9\s\-_]+$/]',
                'errors' => [
                    'required' => 'Product name is required',
                    'min_length' => 'Product name must be at least 3 characters',
                    'max_length' => 'Product name cannot exceed 100 characters',
                    'regex_match' => 'Product name can only contain letters, numbers, spaces, hyphens, and underscores'
                ]
            ],
            'description' => [
                'label' => 'Description',
                'rules' => 'permit_empty|min_length[10]|max_length[500]',
                'errors' => [
                    'min_length' => 'Description must be at least 10 characters if provided',
                    'max_length' => 'Description cannot exceed 500 characters'
                ]
            ],
            'price' => [
                'label' => 'Price',
                'rules' => 'required|decimal|greater_than[0]|less_than_equal_to[999999.99]',
                'errors' => [
                    'required' => 'Price is required',
                    'decimal' => 'Price must be a valid decimal number',
                    'greater_than' => 'Price must be greater than 0',
                    'less_than_equal_to' => 'Price cannot exceed 999,999.99'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            $errorMessage = implode('<br>', $errors);
            session()->setFlashdata('error', $errorMessage);
            return redirect()->back()->withInput();
        }

        $data = [
            'product_name' => trim($this->request->getPost('product_name')),
            'description'  => trim($this->request->getPost('description')),
            'price'        => $this->request->getPost('price'),
        ];

        if ($productModel->update($id, $data)) {
            session()->setFlashdata('message', 'Product updated successfully!');
        } else {
            session()->setFlashdata('error', 'Failed to update product. Please try again.');
        }

        return redirect()->to(base_url('/dashboard'));
    }

    // Delete product
    public function delete($id)
    {
        $productModel = new ProductModel();
        $productModel->delete($id);

        session()->setFlashdata('message', 'Product deleted successfully!');
        return redirect()->to(base_url('/dashboard'));
    }
}
