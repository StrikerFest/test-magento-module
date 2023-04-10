<?php
namespace Tigren\CustomerGroupCatalog\Model;

use Exception;
use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\AttributeSetRepositoryInterface;

class AttributeSetList
{
    /**
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        AttributeSetRepositoryInterface $attributeSetRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->attributeSetRepository = $attributeSetRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * list attribute set
     *
     * @return AttributeSetInterface|null
     */
    public function listAttributeSet()
    {
        $attributeSetList = null;
        try {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $attributeSet = $this->attributeSetRepository->getList($searchCriteria);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        if ($attributeSet->getTotalCount()) {
            $attributeSetList = $attributeSet;
        }

//        if ($attributeSetList) {
//            foreach ($attributeSetList->getItems() as $list) {
//                print("<pre>". print_r(($list->getData())) . "</pre>");
//            }
//        }
        return $attributeSetList;
    }
}
