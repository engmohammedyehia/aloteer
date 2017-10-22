<?php
namespace PHPMVC\Controllers;

use PHPMVC\Models\ClientModel;

class TestController extends AbstractController
{
    public function defaultAction()
    {
        echo sys_get_temp_dir();
    }
}