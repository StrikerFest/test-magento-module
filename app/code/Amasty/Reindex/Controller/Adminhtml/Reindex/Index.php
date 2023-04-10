<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Reindex from Admin for Magento 2
 */

namespace Amasty\Reindex\Controller\Adminhtml\Reindex;

class Index extends \Amasty\Reindex\Controller\Adminhtml\AbstractReindex
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->run();
        return $this->resultRedirectFactory->create()->setRefererUrl();
    }
}
