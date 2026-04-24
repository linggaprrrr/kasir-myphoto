<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    
    }

    public function index() {                            
        return view('login');
    }

    public function loginProcess() {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');        
        
        $user = $this->userModel->getWhere(['username' => $username])->getRow();
        
        if (!is_null($user)) {
            
            if (password_verify($password, $user->password)) {
                session()->set([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'user_type' => 'admin',                    
                ]);    
                            
                
                return redirect()->to(base_url('/dirs'))->with('message', 'Login Successful!');
                
            } else {
                return redirect()->to(base_url('/login'))->with('error', 'Email or password is incorrect');
            }        
        } else {
            return redirect()->to(base_url('/login'))->with('error', 'Email or password is incorrect');
        }        
    }



    public function logout() {
        session()->destroy();
        return redirect()->to(base_url('/login'));
    }

   

    public function test() {
        $users = $this->userModel->get();
        foreach ($users->getResultArray() as $user) {
            if ($user['password'] == 'ec6a6536ca304edf844d1d248a4f08dc') {
                $password = password_hash('admin', PASSWORD_BCRYPT);
                $this->userModel->set('password', $password)
                    ->where('id', $user['id'])
                    ->update();
            } else if ($user['password'] == '81dc9bdb52d04dc20036dbd8313ed055') {
                $password = password_hash('123', PASSWORD_BCRYPT);
                $this->userModel->set('password', $password)
                    ->where('id', $user['id'])
                    ->update();
            }
        }
    }
}
