<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Controller\Adminhtml\Faq;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    protected $_faqFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Tigren\Faq\Model\FaqFactory $faqFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
//        $this->_faqFactory = $faqFactory;
//        return parent::__construct($context);
    }

    public function execute()
    {
        //Call page factory to render layout and page content
        $resultPage = $this->resultPageFactory->create();
        //Set the menu which will be active for this page

        $resultPage->setActiveMenu('Tigren_Faq::faq_manage');
        //Set the header title of grid
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Faqs'));
        //Add bread crumb
//        $resultPage->addBreadcrumb(__('Tigren'), __('Tigren'));
//        $resultPage->addBreadcrumb(__('Faq'), __('Manage Faqs'));

//        $faq = $this->_faqFactory->create();
//        $collection = $faq->getCollection();
//        foreach($collection as $item){
//            echo "<pre>";
//            print_r($item->getData());
//            echo "</pre>";
//        }
//        exit();
        return $resultPage;

    }

    /*
    * Check permission via ACL resource
    */
    protected function _isAllowed()
    {
        return
            $this->_authorization->isAllowed('Tigren_Faq::faq_manage');
    }
}
