<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Plugin;


class FinalPriceBox
{
    function afterToHtml(\Magento\Catalog\Pricing\Render\FinalPriceBox $subject, $result)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $httpContext = $objectManager->get('Magento\Framework\App\Http\Context');
        $isLoggedIn = $httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        if(!$isLoggedIn){
            return 'Please login to see price';
        } else {
            return $result;
        }
    }
}
