<?php
namespace Giftia\action;

class AboutAction extends BaseAction {

    public function indexAction() {
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if ($page == "extra_patch") {
                header("Location: /about/extraPatchDoc");
                return;
            } elseif ($page == "license") {
                 header("Location: /about/agreement");
                return;
            }
        }

        return $this->_genViewResult('about/index', ['active' => 'home']);
    }

    public function agreementAction() {
        return $this->_genViewResult('about/agreement', ['active' => 'agreement']);
    }

    public function extraPatchDocAction() {
        return $this->_genViewResult('about/patch', ['active' => 'agreement']);
    }

    public function contactAction() {
        return $this->_genViewResult('about/contact', ['active' => 'contact']);
    }

}
