<?php
namespace Giftia\action;

class IndexAction extends BaseAction {

    public function indexAction() {
        return $this->_genViewResult('home/index', []);
    }

}
