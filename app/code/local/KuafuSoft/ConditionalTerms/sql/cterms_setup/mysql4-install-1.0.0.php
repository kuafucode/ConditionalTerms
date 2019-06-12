<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
ALTER TABLE `{$this->getTable('checkout/agreement')}`
    ADD COLUMN `conditions_serialized` text NOT NULL;
");

$installer->endSetup();
