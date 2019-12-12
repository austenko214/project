<?php

namespace controllers;

use utils\View;
use utils\Utility;
use models\Users;
use entities\User;

class Main extends BaseController
{
	public function index($msg = '')
	{
		if (!empty($_SESSION['user'])) {
			$this->direction();
			return;
		}
		$vars = [
			'msg' => $msg,
			'forget' => 0,
		];
		View::render($vars, 'authorization');
	}

	public function auth()
	{
		$user = Users::getUser($_POST['login'], $_POST['pwd']);
		if (is_null($user)) {
			$this->index('Ви не пройшли авторизацію!');
			return;
		}
		$_SESSION['user'] = $user;
		$this->direction();
	}

	public function direction()
	{
		$user = $_SESSION['user'];
		Utility::redirect('/' . $user->getCategoryName());
	}

	public function logout()
	{
		session_unset();		
		Utility::redirect('/');
	}

	public function forget()
	{
		$vars = [
			'msg' => '',
			'forget' => 1,
		];
		View::render($vars, 'authorization');
	}

	public function registrate($msg = '', $data = [])
	{
		$data['msg'] = $msg;
		View::render($data, 'student');
	}

	public function sendForget()
	{
		if (empty($_POST['email']))
			Utility::redirect('/');
		$forget = Users::getForget($_POST['email']);
		if ($forget !== false)
			Utility::sendMail($forget);
		Utility::redirect('/');
	}
    
    public function addingStudent()
    {
        if (Users::isUnique($_POST['login'], $_POST['email'], $_POST['position'])) {
            $this->registrate('Логін, адреса поштової скриньки або студентський квиток - не унікальні!', $_POST);
            return;
        }
        if ($_POST['pwd'] !== $_POST['pwd2']) {
            $this->registrate('Паролі не співпадають!', $_POST);
            return;
        }
        $_POST['password'] = $_POST['pwd'];
        unset($_POST['pwd'], $_POST['pwd2']);
        $_POST['name'] = $_POST['name1'] . ' ' . $_POST['name2'] . ' ' . $_POST['name3'];
        unset($_POST['name1'], $_POST['name2'], $_POST['name3']);
        $_POST['category'] = 3;
        $_POST['status'] = 2;
        $user = new User($_POST);
        $user->save(array_keys($_POST));
        $this->index();
    }
}
