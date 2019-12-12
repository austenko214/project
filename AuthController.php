<?php

namespace controllers;

use utils\Utility; 

abstract class AuthController extends BaseController
{
    public function beforeAction()
    {
        parent::beforeAction();
        if (empty($_SESSION['user']))
            Utility::redirect('/');
    }
}
