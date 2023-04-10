<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIG_MODULE_IS_ENABLED = 'customergroupcatalog/general/enable';


    public function getDefaultConfig($path)
    {
        return $this->scopeConfig->getValue($path,
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    public function isModuleEnabled()
    {
        return (bool) $this->getDefaultConfig(self::CONFIG_MODULE_IS_ENABLED);
    }
}
