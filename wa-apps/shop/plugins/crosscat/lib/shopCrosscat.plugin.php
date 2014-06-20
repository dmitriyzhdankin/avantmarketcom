<?php
class shopCrosscatPlugin extends shopPlugin {
    public function addBackendSettings() {
        return array('aux_li' => '<li class="small float-right"><a href="?plugin=crosscat&action=list">Cross Categories</a></li>');
    }
    
}