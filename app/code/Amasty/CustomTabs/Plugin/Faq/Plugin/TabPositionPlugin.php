<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Tabs for Magento 2
 */

namespace Amasty\CustomTabs\Plugin\Faq\Plugin;

class TabPositionPlugin
{
    /**
     * @phpstan-ignore-next-line
     * @param \Amasty\Faq\Plugin\TabPosition $subject
     * @param \Closure $proceed
     * @param $result
     *
     * @return mixed
     */
    public function aroundAfterGetGroupChildNames(
        \Amasty\Faq\Plugin\TabPosition $subject,
        \Closure $proceed,
        $plugin,
        $childNamesSortOrder
    ) {
        //disable module plugin
        return $childNamesSortOrder;
    }
}
