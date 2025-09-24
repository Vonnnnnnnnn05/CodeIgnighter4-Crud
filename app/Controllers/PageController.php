<?php

namespace App\Controllers;

class PageController extends BaseController
{
    public function pc()
    {
        // Load products and pass to the product_card view
        $productModel = new \App\Models\ProductModel();
        $products = $productModel->findAll();

        return view('product_card', [
            'products' => $products,
        ]);
    }
    public function reports()
    {
        // Load products and pass to the reports view
        $productModel = new \App\Models\ProductModel();
        $products = $productModel->findAll();

        return view('reports', [
            'products' => $products,
        ]);
    }
}
