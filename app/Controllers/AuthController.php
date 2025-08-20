<?php namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('auth/login');
    }

    public function process()
    {
        $userModel = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Cari user berdasarkan username
        $user = $userModel->where('username', $username)->first();


        if ($user) {
            // Jika user ditemukan, verifikasi password
            if (password_verify($password, $user['password'])) {
                // Jika password benar, set session
                $sessionData = [
                    'user_id'       => $user['id'],
                    'name'          => $user['name'],
                    'username'      => $user['username'],
                    'isLoggedIn'    => TRUE
                ];
                session()->set($sessionData);
                return redirect()->to('/'); // Redirect ke dashboard
            }
        }

        // Jika user tidak ditemukan atau password salah
        session()->setFlashdata('error', 'Username atau Password salah.');
        return redirect()->to('/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}