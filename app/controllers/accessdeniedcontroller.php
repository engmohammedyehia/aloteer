<?php

namespace PHPMVC\Controllers;

class AccessDeniedController extends AbstractController
{
    public function defaultAction()
    {
        $this->language->load('template.common');
        $this->language->load('accessdenied.default');
        $this->_view();
    }
}