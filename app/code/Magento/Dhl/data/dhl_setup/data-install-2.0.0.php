<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/** @var $this \Magento\Dhl\Model\Resource\Setup */
$days = (new \ResourceBundle(
    $this->getLocaleResolver()->getLocale(),
    'ICUDATA'
))['calendar']['gregorian']['dayNames']['format']['abbreviated'];

$select = $this->getConnection()->select()->from(
    $this->getTable('core_config_data'),
    ['config_id', 'value']
)->where(
    'path = ?',
    'carriers/dhl/shipment_days'
);

foreach ($this->getConnection()->fetchAll($select) as $configRow) {
    $row = [
        'value' => implode(
            ',',
            array_intersect_key(iterator_to_array($days), array_flip(explode(',', $configRow['value'])))
        )
    ];
    $this->getConnection()->update(
        $this->getTable('core_config_data'),
        $row,
        ['config_id = ?' => $configRow['config_id']]
    );
}
