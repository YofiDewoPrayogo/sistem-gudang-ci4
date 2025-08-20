<?php namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $userId = session()->get('user_id'); // Ambil ID user dari session

        $data = [
            'title' => 'Edit Profile',
            'user'  => $userModel->find($userId) // Ambil data user yang sedang login
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        $userModel = new UserModel();
        $userId = session()->get('user_id');

        $rules = [
            'name' => 'required|min_length[3]',
            'username' => "required|min_length[3]|is_unique[users.username,id,{$userId}]"
        ];
        
        $password = $this->request->getVar('password');
        if ($password) {
            $rules['password'] = 'required|min_length[6]';
            $rules['pass_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $data = [
            'id'       => $userId,
            'name'     => $this->request->getVar('name'),
            'username' => $this->request->getVar('username'),
        ];
        
        // Hanya update password jika diisi
        if ($password) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->save($data);

        // Update session jika nama berubah
        session()->set('name', $data['name']);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}