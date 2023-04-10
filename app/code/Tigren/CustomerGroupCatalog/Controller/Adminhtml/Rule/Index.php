<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    protected $_ruleFactory = false;

    public function __construct(Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Tigren\CustomerGroupCatalog\Model\RuleFactory $ruleFactory,
        \Tigren\CustomerGroupCatalog\Model\AttributeSetList $ASL,
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->ruleFactory = $ruleFactory;
        $this->ASL = $ASL;
        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        //Call page factory to render layout and page content
        $resultPage = $this->resultPageFactory->create();
        //Set the menu which will be active for this page
        $resultPage->setActiveMenu('Tigren_Rule::rule_manage');
        //Set the header title of grid
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Rules'));
        return $resultPage;
    }


    /*
    * Check permission via ACL resource
    */
    protected function _isAllowed()
    {
        return
            $this->_authorization->isAllowed('Tigren_Rule::rule_manage');
    }
}
