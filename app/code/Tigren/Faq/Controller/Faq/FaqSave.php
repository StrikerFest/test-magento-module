<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Controller\Faq;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\Result\PageFactory;
use Tigren\Faq\Model\FaqFactory;
use Tigren\Faq\Model\ResourceModel\Faq as FaqResource;
use Magento\Framework\Controller\ResultFactory;

class FaqSave extends Action
{
//    /** @var PageFactory $resultPageFactory */
//    protected $resultPageFactory;
//
//    /**
//     * Result constructor.
//     * @param Context $context
//     * @param PageFactory $pageFactory
//     */
    public function __construct(
        Context $context,
        private FaqFactory $faqFactory,
        private FaqResource $resource,
        )
    {
//        $this->resultPageFactory = $pageFactory;
        parent::__construct($context);
    }

//    /**
//     * The controller action
//     *
//     * @return \Magento\Framework\View\Result\Page
//     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if($data) {
            $model = $this->faqFactory->create();
            if (empty($data['faq_id'])) {
                $data['faq_id'] = null;
            }
            if(empty($data['question']) || empty($data['author'])){
                $this->messageManager->addErrorMessage(__("Please fill the form in completion"));
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;

            }
            $model->setData($data);
            $this->resource->save($model);
            //            dd($this->resource);
            $this->messageManager->addSuccessMessage(__("Question added successfully"));
//            return $resultRedirect->setPath('*/*/');
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            // Your code

            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
//            dd($resultRedirect);
            return $resultRedirect;
        }




        $number = $this->getRequest()->getParam('author');
        $resultPage = $this->resultPageFactory->create();

//        /** @var Messages $messageBlock */
//        $messageBlock = $resultPage->getLayout()->createBlock(
//            'Magento\Framework\View\Element\Messages',
//            'answer'
//        );
//        if (is_numeric($number)) {
//            $messageBlock->addSuccess($number . ' times 2 is ' . ($number * 2));
//        } else {
//            $messageBlock->addError('You didn\'t enter a number!');
//        }
//
//        $resultPage->getLayout()->setChild(
//            'content',
//            $messageBlock->getNameInLayout(),
//            'answer_alias'
//        );

        return $resultPage;
    }
//    public function execute()
//    {
//        echo 'Faq! asdasdasdasdasd';
//        exit;
//    }
}
