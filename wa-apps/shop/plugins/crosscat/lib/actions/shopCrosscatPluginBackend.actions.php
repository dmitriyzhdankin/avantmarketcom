<?php
class shopCrosscatPluginBackendActions extends waViewActions {
    public function __construct($system = null) {
        parent::__construct($system);
        $this->setLayout(new shopBackendLayout());
        //waSystem::getInstance()->getResponse()->addCss('wa-apps/shop/plugins/custommenu/css/backend.css');
        waSystem::getInstance()->getResponse()->addJs('wa-apps/shop/plugins/crosscat/js/crosscat_backend.js');
    }
    function listAction() {
        $this->setTemplate('/../../backend/ListCrossCat');
        
        
        $CCM = new shopCrosscatModel();
        $cross = $CCM->select('*')->fetchAll();
        $cross_ids = array();
        foreach($cross as $cat) {
            $cross_ids[$cat['cat_id']] = $cat['cross_ids'];
        }
        
        
        if($data = waRequest::post()) {
            //echo '<pre>';var_dump($data);die;
            $CCM = new shopCrosscatModel();
            //$cross = $CCM->fetchField('cat_id');
            foreach($data['cross'] as $id => $rows) {
                //var_dump($rows);die;
                if(isset($cross_ids[$id])) {
                    $CCM->updateByField(array('cat_id' => $id), array('cross_ids' => serialize($rows) ));
                } else {
                    $CCM->insert(array('cat_id' => $id, 'cross_ids' => serialize($rows) ));
                }
            }
        }
        $cross = $CCM->select('*')->fetchAll();
        echo '<pre>';var_dump($cross );die;
        $CM = new shopCategoryModel();
        $cats = $CM->select('*')->order('name ASC')->fetchAll();
        foreach($cats as &$cat) {
            $cat['cross'] = array();
            foreach($cross as $cros_ids) {
                if( $cros_ids['cat_id'] == $cat['id']) {
                    $cat['cross'] = unserialize($cros_ids['cross_ids']);
                }
            }
        }
        
        $this->view->assign('cats', $cats);
        
        $category_model = new shopCategoryModel();
        $categories = $category_model->getFullTree('id, name, depth', true);
        
        $CCM = new shopCrosscatModel();
        foreach($categories as &$cat) {
            
            $cross = $CCM->select('cross_ids')->where('cat_id = '.$cat['id'])->fetchAll();
            //var_dump($cross);die;
        }
        
        
        $this->view->assign('categories', $categories);
    }
}