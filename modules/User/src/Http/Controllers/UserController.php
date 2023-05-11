<?php

namespace Modules\User\src\Http\Controllers;
use App\Http\Controllers\Controller;
use Modules\User\src\Models\User;

class UserController extends Controller {
    
    public function index()
    {
        return view('user::lists');
    }

    public function thanhtoan()
    {
        return view('user::vnpay_php.vnpay_php.index');
    }

    public function detail($id)
    {
        return view('user::new',compact('id'));
    }

    public function create(){
        $user = new User();
        $user->name = 'Hello';
        $user->email = 'My friend';
        $user->save();
    }

    
}