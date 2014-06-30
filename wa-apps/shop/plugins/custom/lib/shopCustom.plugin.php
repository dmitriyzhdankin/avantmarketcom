<?php

class shopCustomPlugin extends shopPlugin {
    
    protected static $plugin;
    protected static $added_js = false;
    
    public function __construct($system = null) {
        parent::__construct($system);
        if(!self::$added_js) {
            self::$added_js = true;
            waSystem::getInstance()->getResponse()->addJs('wa-apps/shop/plugins/custom/js/custom_backend.js');
            waSystem::getInstance()->getResponse()->addJs('wa-apps/shop/plugins/custom/js/custom_backend_change_price_count_products.js');
            waSystem::getInstance()->getResponse()->addCss('wa-apps/shop/plugins/custom/css/custom_backend.css');
        }
    }
    
    public function addBackendSettingsScript() {
//        return array(
//            'sidebar_bottom_li' => '<script type="text/javascript" src="/wa-apps/shop/plugins/custom/js/custom_backend.js">'
//        );
    }
    
    public function addCategoriesForProduct() {
        //waSystem::getInstance()->getResponse()->addJs('wa-apps/shop/plugins/custom/js/custom_backend.js');
        $action = new shopCustomPluginBackendProductCategoriesAction();
        return array('edit_basics' => $action->display(false));
    }
    
    public function addCopyProductsButton() {
//        waSystem::getInstance()->getResponse()->addJs('wa-apps/shop/plugins/custom/js/custom_backend.js');
        
//        $action = new shopCustomPluginBackendProductCategoriesAction();
        return array('toolbar_section' => '<div class="block"><div class="copy-products" data-action="copy"><a href="#"><i class="icon16 folders"></i>Copy products</a></div></div>');
    }
    
    protected static function getThisPlugin() {
        if (self::$plugin) {
            return self::$plugin;
        } else {
            return wa()->getPlugin('custom');
        }
    }
    public static function getSameProducts() {
        $plugin = self::getThisPlugin();
        $product_model = new shopProductModel();
        $product = $product_model->getByField('url', waRequest::param('product_url'));
        if ( $product && $product['category_id']) {
            $collection = new shopProductsCollection('category/'.$product['category_id']);
            $same_products = $collection->getProducts('*',1000);
            if( $same_products ) {
                return $same_products;
            }
        }
        return false;
    }
    
    public static function getProductCategory() {
        $plugin = self::getThisPlugin();
        $product_model = new shopProductModel();
        $product = $product_model->getByField('url', waRequest::param('product_url'));
        if ( $product && $product['category_id']) {
            $category_model = new shopCategoryModel();
            $category = $category_model->getById($product['category_id']);
            return $category['name'];
        }
        return false;
    }
    
    public static function getReviewsCount($product_id) {
        $plugin = self::getThisPlugin();
        $reviews_model = new shopProductReviewsModel();
        return $reviews_model->count($product_id, false);
    }
    
    public static function getCustomTitle() {
        $action = waRequest::param('action');
        $title = null;
        $description = null;
        $keywords = null;
        switch( $action ) {
            case 'product' : {
                $product_model = new shopProductModel();
                $product = $product_model->getByField('url', waRequest::param('product_url'));
                $product_name = trim($product['name']);
                if( !trim($product['meta_title']) || $product_name == trim($product['meta_title']) ) {
                    $title = $product_name . ' оптом, от официального представителя в России | Авантмаркет';
                }
                if( !$product['meta_description'] ) {
                    $category_model = new shopCategoryModel();
                    $category = $category_model->getById($product['category_id']);
                    $category_name = isset($category['name']) ? trim(mb_strtolower($category['name'])).' ' : '';
                    $product_feature_model = new shopProductFeaturesModel();
                    $product['features'] = $product_feature_model->getValues($product['id']);
                    $brand_name = isset($product['features']['brand']) ? trim($product['features']['brand']).' ' : '';

                    $description = 'Официальный представитель '. $category_name . $brand_name .'в Росии предлагает купить '. $product_name . ' оптом. Всегда самая низкая цена, лучшие условия сотрудничества, гарантия и сервис. Авантмаркет';
                }
                if( !$product['meta_keywords'] ) {
                    $keywords = $product_name .' купить оптом, продажа '. $product_name .' опт';
                }
                break;
            }
            case 'category' : {
                
                $category_model = new shopCategoryModel();
                $category = $category_model->getById(waRequest::param('category_id'));
                $category_name = trim($category['name']);
                if( !$category['parent_id'] ) {
                    if( !trim($category['meta_title']) || trim($category['meta_title']) == $category_name ) {
                        $title = $category_name .' опт, оптовая продажа от официального представителя в России |  Авантмаркет';
                    }
                    if( !$category['meta_description'] ) {
                        $description = 'Оптовая компания Авантмаркет предлагает купить оптом '. $category_name .' для отдыха и спорта. Только качественная продукция по низкой цене.';
                    }
                    if( !$category['meta_keywords'] ) {
                        $keywords = $category_name .' оптом, купить '. $category_name .' опт';
                    }
                } else {
                    $parent_category = $category_model->getById($category['parent_id']);
                    if( !trim($category['meta_title']) || trim($category['meta_title']) == $category_name ) {
                        $title = trim($parent_category['name']) .' '. mb_strtolower($category_name) .' оптом, оптовая продажа фирменного снаряжения от официального представителя в России |  Авантмаркет';
                    }
                    if( !$category['meta_description'] ) {
                        $description = 'Оптовая компания Авантмаркет предлагает купить оптом '. trim(mb_strtolower($parent_category['name'])) .' '. mb_strtolower($category_name) .' для отдыха и спорта. Только качественная продукция по низкой цене.';
                    }
                    if( !$category['meta_keywords'] ) {
                        $keywords = trim(mb_strtolower($parent_category['name'])) .' '. mb_strtolower($category_name) .' оптом, купить '. trim(mb_strtolower($parent_category['name'])) .' '. mb_strtolower($category_name) .' опт';
                    }
                }
                break;
            }
            case 'brand' : {
                $brand = waRequest::param('brand');
                $title = 'Бренд '. $brand .' купить оптом, официальный представитель в России, кампания Авантмаркет';
                $description = 'Официальный представитель бренда '. $brand .' в России, кампания Авантмаркет, предлагает купить продукцию бренда '. $brand .' оптом. Низкие цены, высокий уровень сервиса и лояльности, сервис и гарантия.';
                $keywords = 'купить '. $brand .' оптом, продажа '. $brand .' опт';
                break;
            }
        }
        
        if($title) wa()->getResponse()->setTitle($title);
        if($description) wa()->getResponse()->setMeta('description',$description);
        if($keywords) wa()->getResponse()->setMeta('keywords',$keywords);
        
        
//        $product_model = new shopProductModel();
//        $category_model = new shopCategoryModel();
//        $product_feature_model = new shopProductFeaturesModel();
//        
//        $products = $product_model->select('id,category_id,name')->fetchAll();
//        for( $i=0; $i < count($products); $i++ ) {
//            $product = $products[$i];
//            $product_name = trim($product['name']);
//            $title = $product_name . ' оптом, от официального представителя в России | Авантмаркет';
//
//            $category = $category_model->getById($product['category_id']);
//            $category_name = isset($category['name']) ? trim(mb_strtolower($category['name'])).' ' : '';
//            $product['features'] = $product_feature_model->getValues($product['id']);
//            $brand_name = isset($product['features']['brand']) ? trim($product['features']['brand']).' ' : '';
//
//            $description = 'Официальный представитель '. $category_name . $brand_name .'в Росии предлагает купить '. $product_name . ' оптом. Всегда самая низкая цена, лучшие условия сотрудничества, гарантия и сервис. Авантмаркет';
//            $keywords = $product_name .' купить оптом, продажа '. $product_name .' опт';
//            
//            $product_model->updateById($product['id'], array('meta_title'=>$title,'meta_description'=>$description,'meta_keywords'=>$keywords) );
//        }
//        
//        $categories = $category_model->select('id,parent_id,name')->fetchAll();
//        for( $i=0; $i < count($categories); $i++ ) {
//            $category = $categories[$i];
//            $category_name = trim($category['name']);
//            if( !$category['parent_id'] ) {
//                $title = $category_name .' опт, оптовая продажа от официального представителя в России |  Авантмаркет';
//                $description = 'Оптовая компания Авантмаркет предлагает купить оптом '. $category_name .' для отдыха и спорта. Только качественная продукция по низкой цене.';
//                $keywords = $category_name .' оптом, купить '. $category_name .' опт';
//            } else {
//                $parent_category = $category_model->getById($category['parent_id']);
//                $parent_category_name = trim($parent_category['name']);
//                $category_name = str_replace($parent_category_name.' ', '', $category_name);
//                $title = $parent_category_name .' '. mb_strtolower($category_name) .' оптом, оптовая продажа фирменного снаряжения от официального представителя в России |  Авантмаркет';
//                $description = 'Оптовая компания Авантмаркет предлагает купить оптом '. mb_strtolower($parent_category_name) .' '. mb_strtolower($category_name) .' для отдыха и спорта. Только качественная продукция по низкой цене.';
//                $keywords = mb_strtolower($parent_category_name) .' '. mb_strtolower($category_name) .' оптом, купить '. mb_strtolower($parent_category_name) .' '. mb_strtolower($category_name) .' опт';
//            }
//            
//            $category_model->updateById($category['id'], array('meta_title'=>$title,'meta_description'=>$description,'meta_keywords'=>$keywords) );
//        }
   }
}