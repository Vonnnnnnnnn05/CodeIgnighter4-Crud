<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function loginform()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            session()->set('user', [
                'user_id'  => $user['user_id'],
                'username' => $user['username']
            ]);

            // ✅ Flashdata for SweetAlert
            return redirect()->to('/dashboard')->with('success', 'Welcome ' . $user['username']);
        } else {
            // ❌ Invalid credentials
            return redirect()->back()->with('error', 'Invalid username or password');
        }
    }
    public function registerUser()
    {
        $userModel = new UserModel();

        // Validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[50]|is_unique[user.username]',
                'errors' => [
                    'required' => 'Username is required',
                    'min_length' => 'Username must be at least 3 characters',
                    'max_length' => 'Username cannot exceed 50 characters',
                    'is_unique' => 'Username already exists'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[user.email]',
                'errors' => [
                    'required' => 'Email is required',
                    'valid_email' => 'Please provide a valid email address',
                    'is_unique' => 'Email already exists'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password is required',
                    'min_length' => 'Password must be at least 6 characters'
                ]
            ]
        ]);

        // ✅ Check if validation fails
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare data to save
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        // Try saving and surface DB/model errors back to the user
        try {
            $saved = $userModel->save($data);
            if ($saved === false) {
                // Collect model errors if any
                $errors = $userModel->errors();
                $msg = is_array($errors) ? implode(' ', $errors) : 'Unable to save user.';
                return redirect()->back()->withInput()->with('error', $msg);
            }
        } catch (\Throwable $e) {
            // Log the exception for debugging and return a friendly message
            if (function_exists('log_message')) {
                log_message('error', 'User save failed: ' . $e->getMessage());
            }
            return redirect()->back()->withInput()->with('error', 'Registration failed: database error. Check logs.');
        }

        // ✅ Redirect to login with a flash message
        return redirect()->to('/')->with('success', 'Registration successful! Please log in.');
    }

    public function logout()
    {
        session()->destroy();        // If the request includes ?silent=1 we skip setting a flash message
        $silent = $this->request->getGet('silent');
        if ($silent === '1') {
            return redirect()->to('/');
        }
        return redirect()->to('/')->with('success', 'Logged Out Successfully');
    }
}
