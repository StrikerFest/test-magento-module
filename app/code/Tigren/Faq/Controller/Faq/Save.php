<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Tigren\Faq\Model\FaqFactory;
use Tigren\Faq\Model\ResourceModel\Faq as FaqResource;

class Save extends Action implements HttpPostActionInterface
{

    public function __construct(
        Context $context,
        private FaqFactory $faqFactory,
        private FaqResource $resource
    ){
        parent::__construct($context);
    }
    public function execute(): ResultInterface
    {
        // TODO: Implement execute() method.
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if($data){
            $model =$this->faqFactory->create();
            if(empty($data['faq_id'])){
                $data['faq_id'] = null;
            }

            $model->setData($data);

            try {
            $this->resource->save($model);
//            dd($this->resource);
            $this->messageManager->addSuccessMessage(__("Faq saved"));
            return $resultRedirect->setPath('*/*/');
            }
            catch (LocalizedException $exception){
                $this->messageManager->addExceptionMessage($exception);
                die("LocalizedException");
            }
            catch (\Throwable $e){
                $this->messageManager->addErrorMessage(__("IT DIED saving faq"));
                die("Throwable");
            }
        }


        return $resultRedirect->setPath('*/*/');
    }
}
