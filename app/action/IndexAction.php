<?php
namespace Giftia\action;

class IndexAction extends BaseAction {

    public function indexAction() {
        if ($_SERVER['HTTP_HOST'] == "kdays.cn") {
            return $this->_genViewResult("home/kd", []);
        }
        return $this->_genViewResult('home/index', []);
    }

}
