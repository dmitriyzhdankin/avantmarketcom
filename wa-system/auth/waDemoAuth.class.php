<?php 

/*
 * This file is part of Webasyst framework.
 *
 * Licensed under the terms of the GNU Lesser General Public License (LGPL).
 * http://www.webasyst.com/framework/license/
 *
 * @link http://www.webasyst.com/
 * @author Webasyst LLC
 * @copyright 2011 Webasyst LLC
 * @package wa-system
 * @subpackage auth
 */
class waDemoAuth extends waAuth
{
    protected $options = array(
        'cookie_expire' => 2592000
    );

    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->options['description'] = "Enter any login and password so<br> we can create a user account for you";

        if (waRequest::method() == 'get') {
            $view = wa()->getView();
            if (!$view->getVars('login')) {
                $view->assign('login', 'admin'.rand(1, 99));
            }
            if (!$view->getVars('password')) {
                $view->assign('password', '123456');
            }
        }
    }

    protected function _auth($params)
    {
        $model = new waContactModel();
        $login = null;
        if ($params && isset($params['id'])) {
            $contact_model = new waContactModel();
            $user_info = $contact_model->getById($params['id']);
            if ($user_info && ($user_info['is_user'] || !$this->options['is_user'])) {
                waSystem::getInstance()->getResponse()->setCookie('auth_token', null, -1);
                return $this->getAuthData($user_info);
            }
            return false;
        } elseif ($params && isset($params['login']) && isset($params['password'])) {
            $login = $params['login'];
            $password = $params['password'];
        }
        if (waRequest::getMethod() == 'post' && waRequest::post('wa_auth_login')) {
            $login = waRequest::post('login');
            $password = waRequest::post('password');
            if (!strlen($login)) {
                throw new waException(_ws('Login is required'));
            }
        }
        if (strlen($login)) {
            $user_info = $model->getByField('login', $login);
            if (!$user_info) {
                $user = new waUser();
                $user['is_user'] = 1;
                $user['login'] = $login;
                $user['firstname'] = $login;
                $user['password'] = $password;
                $user['locale'] = 'ru_RU';
                $user['timezone'] = 'Europe/Moscow';
                $user['about'] = '';
                $user->save();
                // rights

                $apps = wa()->getApps();
                foreach ($apps as $app_id => $app) {
                    if ($app_id == 'photos') {
                        $user->setRight($app_id, 'backend', 1);
                        //$user->setRight($app_id, 'edit', 1);
                        //$user->setRight($app_id, 'pages', 1);
                    } elseif ($app_id == 'blog') {
                        $user->setRight($app_id, 'backend', 1);
                        //$user->setRight($app_id, 'pages', 1);
                        $user->setRight($app_id, 'add_blog', 1);
                        $user->setRight($app_id, 'blog.1', 2);
                        $user->setRight($app_id, 'blog.2', 2);
                    } elseif ($app_id == 'shop') {
                        $user->setRight($app_id, 'backend', 1);
                        $user->setRight($app_id, 'type.all', 1);
                        $user->setRight($app_id, 'orders', 1);
                        $user->setRight($app_id, 'settings', 1);
                        $user->setRight($app_id, 'reports', 1);
                    } else {
                        $user->setRight($app_id, 'backend', 2);
                    }
                }

                // full admin
                // $user->setRight('webasyst', 'backend', 1);
                return $this->getAuthData($user);
            } elseif ($user_info && $user_info['is_user'] &&
                waContact::getPasswordHash($password) === $user_info['password']) {
                $response = waSystem::getInstance()->getResponse();
                // if remember
                if (waRequest::post('remember')) {
                    $response->setCookie('auth_token', $this->getToken($user_info), time() + 2592000);
                    $response->setCookie('remember', 1);
                } else {
                    $response->setCookie('remember', null, -1);
                }
                // return array with compact user info
                return $this->getAuthData($user_info);
            } else {
                throw new waException(_ws('Invalid login or password'));
            }
        } else {
            // try auth by cookie
            return $this->_authByCookie();
        }
        return false;
    }


}
