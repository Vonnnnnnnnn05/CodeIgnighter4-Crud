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

        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'description'  => $this->request->getPost('description'),
            'price'        => $this->request->getPost('price'),
        ];

        $productModel->insert($data);

        session()->setFlashdata('message', 'Successfully Inserted!');
        return redirect()->to(base_url('/'));
    }

    // Update product
    public function update($id)
    {
        $productModel = new ProductModel();

        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'description'  => $this->request->getPost('description'),
            'price'        => $this->request->getPost('price'),
        ];

        $productModel->update($id, $data);

        session()->setFlashdata('message', 'Product updated successfully!');
        return redirect()->to(base_url('/'));
    }

    // Delete product
    public function delete($id)
    {
        $productModel = new ProductModel();
        $productModel->delete($id);

        session()->setFlashdata('message', 'Product deleted successfully!');
        return redirect()->to(base_url('/'));
    }
}
