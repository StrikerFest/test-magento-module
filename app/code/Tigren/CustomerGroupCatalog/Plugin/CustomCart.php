<?php
/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Tigren\CustomerGroupCatalog\Plugin;


class CustomCart
{
    protected $quoteRepository;
    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }
    public function beforeSave(
        \Magento\Quote\Model\Quote\Item\Repository $subject,
        \Magento\Quote\Api\Data\CartItemInterface $cartItem
    )
    {
        $cartId = $cartItem->getQuoteId();
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->getShippingAddress();
    }
}
