<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Model;
class History extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'Tigren_customer_group_catalog_history';
    protected function _construct()
    {
        $this->_init('Tigren\CustomerGroupCatalog\Model\ResourceModel\History');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

//    RM AFTER
//    public function getConditionsFieldSetId($formName = '')
//    {
//        return $formName . 'rule_conditions_fieldset_' . $this->getId();
//    }

}

