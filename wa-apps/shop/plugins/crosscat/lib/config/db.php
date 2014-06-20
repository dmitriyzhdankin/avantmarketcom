<?php
return array(
    'shop_crosscat' => array(
        'cat_id' => array('int', 11, 'null' => 0),
        'cross_ids' => array('text')
    ),
);

/**
CREATE TABLE IF NOT EXISTS `shop_crosscat` (
  `cat_id` int(11) NOT NULL,
  `cross_ids` text NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
 */