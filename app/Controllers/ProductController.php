<?php
namespace App\Controllers;
use App\Models\ProductModel;

class ProductController extends BaseController
{
    // Show all products
    public function index()
    {
        $productModel = new ProductModel();
        $data['products'] = $productModel->findAll();
        return view('product_view', $data);
    }

    // Show create form
    public function createv()
    {
        return view('create_view');
    }

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
// Controller
session()->setFlashdata('message', 'Successfully Inserted!');
return redirect()->to(base_url('/'));

}
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
      public function delete($id)
    {
        $productModel = new ProductModel();
        $productModel->delete($id);

        session()->setFlashdata('message', 'Product deleted successfully!');
        return redirect()->to(base_url('/'));
    }
}
