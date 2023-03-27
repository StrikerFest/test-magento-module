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
    ) {
        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        // TODO: Implement execute() method.
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->faqFactory->create();
            // Nếu id chưa được đặt - Id = null
            if (empty($data['faq_id'])) {
                $data['faq_id'] = null;
            }
            // Status mặc định là Answered - Nếu ko có câu trả lời nó sẽ chuyển sang Pending và reset position = 0
            $data['status'] = 'Answered';
            if (empty($data['answer'])) {
                $data['status'] = 'Pending';
                $data['position'] = 0;
            }

            // Position
            // Pending = 0 (hiển thị đầu tiên bên admin)
            // Answered = 1 (default)
            // Answered = bất cứ số nào > 1 (số to hơn sẽ được hiển thị đầu tiên bên khách hàng)
            if(empty($data['position'])){
                switch($data['status']){
                    case 'Answered':
                        $data['position'] = 1;
                        break;
                    case 'Pending':
                        $data['position'] = 0;
                }
            }


            $model->setData($data);

            try {
                $this->resource->save($model);
                $this->messageManager->addSuccessMessage(__("Faq saved"));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addExceptionMessage($exception);
                die("LocalizedException");
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(__("IT DIED saving faq"));
                die("Throwable");
            }
        }


        return $resultRedirect->setPath('*/*/');
    }
}
