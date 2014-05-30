<?php
return array (
  'contactinfo' => 
  array (
    'name' => 'Контактная информация',
    'fields' => 
    array (
      'lastname' => 
      array (
        'localized_names' => 'Фамилия',
        'required' => '',
      ),
      'firstname' => 
      array (
        'localized_names' => 'Имя',
        'required' => '',
      ),
      'middlename' => 
      array (
        'localized_names' => 'Отчество',
        'required' => '',
      ),
      'organizatsiya' => 
      array (
        '__dummy__' => 1,
      ),
      'phone' => 
      array (
        'localized_names' => 'Телефон',
        'required' => '',
      ),
      'email' => 
      array (
        'localized_names' => 'Email',
        'required' => '',
      ),
      'address' => 
      array (
        'localized_names' => 'Адрес',
        'fields' => 
        array (
          'city' => 
          array (
            'localized_names' => 'Город',
            'required' => '',
          ),
          'region' => 
          array (
            'localized_names' => 'Регион',
            'required' => '',
          ),
          'perevozchik' => 
          array (
            '__dummy__' => 1,
          ),
          'sklad-perevozch' => 
          array (
            '__dummy__' => 1,
          ),
          'primechanie' => 
          array (
            '__dummy__' => 1,
          ),
        ),
      ),
      'address.shipping' => 
      array (
        'localized_names' => 'Адрес доставки',
        'fields' => 
        array (
          'city' => 
          array (
            'localized_names' => 'Город',
            'required' => '',
          ),
          'region' => 
          array (
            'localized_names' => 'Регион',
            'required' => '',
          ),
          'perevozchik' => 
          array (
            '__dummy__' => 1,
          ),
          'sklad-perevozch' => 
          array (
            '__dummy__' => 1,
          ),
          'primechanie' => 
          array (
            '__dummy__' => 1,
          ),
        ),
      ),
    ),
  ),
  'payment' => true,
  'confirmation' => true,
);