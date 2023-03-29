<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface
{
    protected $_ruleFactory;
    public function __construct(\Tigren\CustomerGroupCatalog\Model\RuleFactory
    $ruleFactory) {
        $this->_ruleFactory = $ruleFactory;
    }
    public function install(ModuleDataSetupInterface $setup,
        ModuleContextInterface $context)
    {
        $data = [
            'name' => "Cool rule",
            'discountAmount' => "20",
            'startTime' => "2023-03-13 00:00:00",
            'endTime' => "2023-05-13 00:00:00",
            'priority' => "123",
            'active' => "1",
        ];
        $rule = $this->_ruleFactory->create();
        $rule->addData($data)->save();
    }
}
