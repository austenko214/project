<?php

namespace controllers;

use utils\View;
use utils\Utility;

class Teacher extends AuthController
{
    public function beforeAction()
    {
        parent::beforeAction();
        if ($_SESSION['user']->getCategoryName() !== 'teacher') 
            Utility::redirect('/');
    }

	public function index()
	{
        $this->profile();    
    }
    
    public function profile()
    {
		$vars = [
            'msg' => '',
        ];
        View::render($vars, 'profile');    
    }
    
    public function subjects()
    {
		$vars = [
            'msg' => '',
        ];
        View::render($vars, 'subjects');    
    }
    
    public function materials()
    {
		$vars = [
            'msg' => '',
        ];
        View::render($vars, 'materials');    
    }
    
    public function messages()
    {
		$vars = [
            'msg' => '',
        ];
        View::render($vars, 'messages');    
    }
    
    public function students()
    {
		$vars = [
            'msg' => '',
        ];
        View::render($vars, 'blocked_students');    
    }
}
