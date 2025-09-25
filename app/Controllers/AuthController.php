<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\UserModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

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
    // Show forgot password form
    public function forgotForm()
    {
        return view('auth/forgot_password');
    }

    // Handle form: create signed token (no DB) and send reset email
    public function sendReset()
    {
        $email = $this->request->getPost('email');
        if (empty($email)) {
            return redirect()->back()->with('error', 'Please provide your email.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Email not found.');
        }

        // create signed token: userId|expires|hmac
        $userId = $user[$userModel->primaryKey] ?? $user['user_id'] ?? null;
        $expires = time() + 3600; // 1 hour
        $payload = $userId . '|' . $expires;

        $appConfig = config('App');
        $key = $appConfig->encryptionKey ?: getenv('APP_KEY') ?: 'change_this_key';
        $sig = hash_hmac('sha256', $payload, $key);
        $token = rtrim(strtr(base64_encode($payload . '|' . $sig), '+/', '-_'), '=');

        $link = base_url("reset-password?token={$token}");

        // Try using PHPMailer (Composer) if available, otherwise fall back to CodeIgniter Email
        $emailConfig = config('Email');
        $sent = false;

        if (class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'von.vergara.399@gmail.com';       // your Gmail
                $mail->Password   = 'ohdh dksv zyvd ihsn';          // Gmail App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
                $mail->Port       = 587;


                $fromEmail = $emailConfig->fromEmail ?: 'no-reply@example.local';
                $fromName  = $emailConfig->fromName  ?: 'SI-CRUD';
                $mail->setFrom($fromEmail, $fromName);
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Password reset request';
                $mail->Body    = view('emails/reset_password', ['link' => $link, 'expires' => 60]);

                $mail->send();
                $sent = true;
            } catch (PHPMailerException $e) {
                log_message('error', 'PHPMailer exception: ' . $e->getMessage());
                // fallthrough to CI mailer
                $sent = false;
            } catch (\Throwable $e) {
                log_message('error', 'PHPMailer error: ' . $e->getMessage());
                $sent = false;
            }
        }

        if (! $sent) {
            // Fallback to CodeIgniter Email service (existing behavior)
            $emailer = \Config\Services::email();
            $from = $emailConfig->fromEmail ?: 'no-reply@example.local';
            $fromName = $emailConfig->fromName ?: 'SI-CRUD';
            $emailer->setFrom($from, $fromName);
            $emailer->setTo($email);
            $emailer->setSubject('Password reset request');
            $message = view('emails/reset_password', ['link' => $link, 'expires' => 60]);
            $emailer->setMessage($message);

            if (! $emailer->send()) {
                log_message('error', 'Reset email failed: ' . $emailer->printDebugger(['headers']));
                return redirect()->back()->with('error', 'Unable to send reset email. Try again later.');
            }
        }

        return redirect()->to('/')->with('success', 'Reset link sent. Check your email.');
    }

    // Show reset form (token in query)
    public function resetForm()
    {
        $token = $this->request->getGet('token');
        if (!$token) {
            return redirect()->to('/')->with('error', 'Invalid reset link.');
        }

        $valid = $this->validateResetToken($token);
        if ($valid === false) {
            return redirect()->to('/')->with('error', 'Reset link expired or invalid.');
        }

        // pass token to view (hidden input)
        return view('auth/reset_password', ['token' => $token]);
    }

    // Accept new password and update user
    public function resetPassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $password_confirm = $this->request->getPost('password_confirm');

        if (!$token) {
            return redirect()->to('/')->with('error', 'Invalid request.');
        }

        $valid = $this->validateResetToken($token);
        if ($valid === false) {
            return redirect()->to('/')->with('error', 'Reset link expired or invalid.');
        }

        // basic validation
        if (empty($password) || strlen($password) < 6 || $password !== $password_confirm) {
            return redirect()->back()->withInput()->with('error', 'Passwords must match and be at least 6 characters.');
        }

        $userModel = new UserModel();
        $updated = $userModel->update($valid['user_id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        if ($updated === false) {
            return redirect()->back()->with('error', 'Unable to update password. Try again.');
        }

        return redirect()->to('/')->with('success', 'Password updated. Please login.');
    }

    // Helper: validate token and return ['user_id'=>.., 'expires'=>..] or false
    private function validateResetToken(string $token)
    {
        $decoded = base64_decode(str_replace(['-', '_'], ['+', '/'], $token));
        if (!$decoded) {
            return false;
        }
        $parts = explode('|', $decoded);
        if (count($parts) !== 3) {
            return false;
        }
        list($userId, $expires, $sig) = $parts;
        if ((int)$expires < time()) {
            return false;
        }

        $payload = $userId . '|' . $expires;
        $appConfig = config('App');
        $key = $appConfig->encryptionKey ?: getenv('APP_KEY') ?: 'change_this_key';
        $expected = hash_hmac('sha256', $payload, $key);

        if (!hash_equals($expected, $sig)) {
            return false;
        }

        return ['user_id' => $userId, 'expires' => $expires];
    }
    // ...existing code...

}
