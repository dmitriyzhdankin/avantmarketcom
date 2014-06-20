<?php
class webasystCreateSystempluginCli extends waCliController
{
    public function execute()
    {
        $type = waRequest::param(0);
        $types = array('sms', 'payment', 'shipping');
        $id = waRequest::param(1);
        $params = waRequest::param();
        $pattern = '/^[a-z][a-z0-9]*$/';
        if (empty($type) || empty($params) || isset($params['help']) || !in_array($type, $types) || !preg_match($pattern, $id)) {
            if (preg_match('/^webasyst(\w+)Cli$/', __CLASS__, $matches)) {
                $callback = create_function('$m', 'return strtolower($m[1]);');
                $action = preg_replace_callback('/^([\w]{1})/', $callback, $matches[1]);
            }
            print("Usage: php wa.php {$action} TYPE ID\n\t[ -name PLUGIN_NAME]\n\t[ -version PLUGIN_VERSION]\n\t[ -settings]\n\t[ -icon]\n");
            print("\n\tTYPE=".implode('|', $types)."\n");
        } else {
            $plugin_path = wa()->getConfig()->getPath('plugins').'/'.$type.'/'.$id;
            $this->create($type, $id, $plugin_path, $params);
        }
    }

    protected function create($type, $id, $path, $params = array())
    {
        $template_id = 'wapattern';
        $template_path = wa()->getConfig()->getPath('plugins').'/'.$type.'/'.$template_id.'/';

        if (!file_exists($path)) {
            try {
                $path .= '/';
                mkdir($path);
                // lib
                mkdir($path.'lib');
                waFiles::protect($path.'lib');
                $plugin_class = null;

                mkdir($path.'lib/classes');
                // config
                mkdir($path.'lib/config');
                // app description
                $plugin = array(
                    'name'        => empty($params['name']) ? ucfirst($id) : $params['name'],
                    'description' => '',
                    'icon'        => isset($params['icon']) ? ('img/'.$id.'.png') : '',
                    'version'     => $version = empty($params['version']) ? '1.0.0' : $params['version'],
                );

                waUtils::varExportToFile($plugin, $path.'lib/config/plugin.php');

                switch ($type) {
                    case 'payment':
                        #settings
                        if (isset($params['settings'])) {
                            waUtils::varExportToFile(array(), $path.'lib/config/settings.php');
                        }

                        #plugin class
                        $template_class_path = $template_path.'lib/'.$template_id.ucfirst($type).'.class.php';
                        $class_path = $path.'lib/'.$id.ucfirst($type).'.class.php';
                        $template = file_get_contents($template_class_path);
                        waFiles::write($class_path, str_replace($template_id, $id, $template));

                        #plugin template
                        mkdir($path.'templates');
                        waFiles::protect($path.'templates');
                        waFiles::copy($template_path.'templates/payment.html', $path.'templates/payment.html');
                        break;
                    case 'shipping':
                        #settings
                        if (isset($params['settings'])) {
                            waUtils::varExportToFile(array(), $path.'lib/config/settings.php');
                        }

                        #plugin class
                        $template_class_path = $template_path.'lib/'.$template_id.ucfirst($type).'.class.php';
                        $class_path = $path.'lib/'.$id.ucfirst($type).'.class.php';
                        $template = file_get_contents($template_class_path);
                        waFiles::write($class_path, str_replace($template_id, $id, $template));

                        break;
                    default:
                        throw new waException(sprintf("Plugin type %s not support yet", $type));
                        break;
                }

                print("Plugin with id {$id} created");
            } catch (waException $ex) {
                print("Plugin with id {$id} NOT created");
                if (waSystemConfig::isDebug()) {
                    echo $e;
                } else {
                    print "Error:".$ex->getMessage();
                }
                waFiles::delete($path);
            }
        } else {
            print("Plugin with id {$id} already exists");
        }
    }

}
