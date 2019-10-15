<?php

namespace MyShops\Search\Columns\Sets;

use Concrete\Core\Search\Column\Column;
use Concrete\Core\Search\Column\Set;
use MyShops\Search\Columns\YesNoColumn;

/**
 * Columns set for the MyShops shops list.
 */
class Shops extends Set
{
    /**
     * Intializes the instance.
     */
    public function __construct()
    {
        $this->addColumn(new Column(
            's.name',
            t('Name'),
            'getName',
            true
        ));
        $this->addColumn(new YesNoColumn(
            's.enabled',
            tc('Shop', 'Enabled'),
            'isEnabled',
            true
        ));
        $this->addColumn(new Column(
            's.location',
            t('Location'),
            'getLocation',
            true
        ));
        $this->addColumn(new Column(
            's.products',
            t('Products'),
            'getProducts',
            true
        ));
        $this->addColumn(new Column(
            's.comment',
            t('Comment'),
            'getComment',
            true
        ));
        $this->addColumn(new Column(
            's.url',
            t('URL'),
            'getUrl',
            true
        ));

        $defaultSortColumn = $this->getColumnByKey('s.name');
        $this->setDefaultSortColumn($defaultSortColumn, 'asc');
    }
}
