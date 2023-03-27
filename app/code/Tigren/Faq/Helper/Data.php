<?php
/*
 * Copyright (c) 2023.  Magento, Inc. All rights reserved.
 */

namespace Tigren\Faq\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CONFIG_MODULE_IS_ENABLED = 'faq/general/enable';


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
