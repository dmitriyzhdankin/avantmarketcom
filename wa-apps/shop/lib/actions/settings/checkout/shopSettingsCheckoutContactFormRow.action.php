<?php

/**
 * Helper for shopSettingsCheckoutContactFormAction.
 * One <tr> row with field data and editor.
 */
class shopSettingsCheckoutContactFormRowAction extends waViewAction
{
    public function execute()
    {
        $f = waRequest::param('f');
        $fid = waRequest::param('fid');
        $parent = waRequest::param('parent');
        $css_class = waRequest::param('css_class');

        $new_field = false;
        if (!($f instanceof waContactField)) {
            $new_field = true;
            $f = new waContactStringField($fid, '', array(
                'app_id' => 'shop',
            ));
        }

        $prefix = 'options';
        if ($parent) {
            $prefix .= '['.$parent.'][fields]';
        }

        static $ftypes = null;
        if ($ftypes === null) {
            $ftypes = array(
                'NameSubfield' => _w('Text (input)'),
                'Email' => _w('Text (input)'),
                'Address' => _w('Address'),
                'Text' => _w('Text (textarea)'),
                'String' => _w('Text (input)'),
                'Select' => _w('Select'),
                'Phone' => _w('Text (input)'),
                'IM' => _w('Text (input)'),
                'Url' => _w('Text (input)'),
                'Date' => _w('Date'),
                'Composite' => _w('Composite field group'),
                'Checkbox' => _w('Checkbox'),
            );
        }

        $this->view->assign('f', $f);
        $this->view->assign('fid', $fid);
        $this->view->assign('parent', $parent);
        $this->view->assign('prefix', $prefix);
        $this->view->assign('uniqid', 'f'.uniqid());
        $this->view->assign('new_field', $new_field);
        $this->view->assign('tr_classes', $css_class);
        $this->view->assign('ftypes', $ftypes);
    }
}
