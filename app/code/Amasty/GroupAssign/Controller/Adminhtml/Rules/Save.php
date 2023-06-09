<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Customer Group Auto Assign for Magento 2
 */

namespace Amasty\GroupAssign\Controller\Adminhtml\Rules;

use Amasty\GroupAssign\Api\RuleRepositoryInterface;
use Amasty\GroupAssign\Controller\Adminhtml\AbstractRules;
use Amasty\GroupAssign\Model\Rule;
use Amasty\GroupAssign\Model\RuleFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

class Save extends AbstractRules
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var DataObject
     */
    protected $dataObject;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RuleRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    /**
     * @var Registry
     */
    private $coreRegistry;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        RuleRepositoryInterface $ruleRepository,
        RuleFactory $ruleFactory,
        DataPersistorInterface $dataPersistor,
        DataObject $dataObject,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->dataObject = $dataObject;
        $this->logger = $logger;
        $this->ruleRepository = $ruleRepository;
        $this->ruleFactory = $ruleFactory;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Save action
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getPostValue()) {
            try {
                $id = (int)$this->getRequest()->getParam('id');
                if ($id) {
                    $model = $this->ruleRepository->getById($id);
                } else {
                    /** @var \Amasty\GroupAssign\Model\Rule $model */
                    $model = $this->ruleFactory->create();
                }

                if (!$this->validateResult($data, $model) || !$this->validateByName($data, $id)) {
                    return $this->saveFormDataAndRedirect($data, $id);
                }
                $this->saveRuleModel($model, $data);

                if ($this->getRequest()->getParam('back')) {
                    return $this->resultRedirectFactory->create()->setPath(
                        'amasty_groupassign/*/edit',
                        ['id' => $model->getId()]
                    );
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                $resultRedirect = $this->resultRedirectFactory->create();
                if (!empty($id)) {
                    $resultRedirect->setPath('amasty_groupassign/*/edit', ['id' => $id]);
                } else {
                    $resultRedirect->setPath('amasty_groupassign/*/new');
                }

                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('Something went wrong while saving the rule data. Please review the error log.')
                );
                $this->logger->critical($e);

                return $this->saveFormDataAndRedirect($data, $id);
            }
        }
        return $this->resultRedirectFactory->create()->setPath('amasty_groupassign/*/');
    }

    /**
     * @param array $data
     * @param \Amasty\GroupAssign\Model\Rule $model
     *
     * @return bool
     */
    private function validateResult($data, $model)
    {
        $validateResult = $model->validateData($this->dataObject->addData($data));

        if ($validateResult !== true) {
            foreach ($validateResult as $errorMessage) {
                $this->messageManager->addErrorMessage($errorMessage);
            }

            return false;
        }

        return true;
    }

    /**
     * @param array $data
     * @param int $id
     *
     * @return bool
     */
    public function validateByName($data, $id)
    {
        $existingRuleId = $this->ruleRepository->getRuleByName($data['rule_name'])->getId();
        if ($existingRuleId != $id && $existingRuleId !== null) {
            $this->messageManager->addErrorMessage('The rule with the same name is already exist');

            return false;
        }

        return true;
    }

    /**
     * @param Rule $model
     * @param array $data
     */
    public function saveRuleModel($model, &$data)
    {
        if (isset($data['rule'])) {
            if (isset($data['rule']['conditions'])) {
                $data['conditions'] = $data['rule']['conditions'];
            }
            unset($data['rule']);
        }
        $model->loadPost($data);
        $this->_getSession()->setPageData($data);
        $this->dataPersistor->set('amasty_groupassign_rule', $data);
        $this->ruleRepository->save($model);

        $this->messageManager->addSuccessMessage(__('The rule is saved.'));
        $this->_getSession()->setPageData(false);
        $this->dataPersistor->clear('amasty_groupassign_rule');
    }

    /**
     * @param \Exception $e
     * @param array $data
     * @param int $id
     */
    private function logError($e, $data, $id)
    {
        $this->logger->critical($e);
        $this->saveFormDataAndRedirect($data, $id);
    }

    /**
     * @param array $data
     * @param int $id
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    private function saveFormDataAndRedirect($data, $id)
    {
        $this->_getSession()->setPageData($data);
        $this->dataPersistor->set('amasty_groupassign_rule', $data);

        return $this->resultRedirectFactory->create()->setPath('amasty_groupassign/*/edit', ['id' => $id]);
    }
}
