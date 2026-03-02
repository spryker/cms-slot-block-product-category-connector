<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface;
use Spryker\Shared\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorConfig;

class ProductCategoryCmsSlotBlockConditionResolver implements ProductCategoryCmsSlotBlockConditionResolverInterface
{
    /**
     * @var \Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface
     */
    protected $productCategoryReader;

    public function __construct(ProductCategoryReaderInterface $productCategoryReader)
    {
        $this->productCategoryReader = $productCategoryReader;
    }

    public function isSlotBlockConditionApplicable(CmsSlotBlockTransfer $cmsSlotBlockTransfer): bool
    {
        return $cmsSlotBlockTransfer->getConditions()
            ->offsetExists(CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY);
    }

    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool {
        /** @var \Generated\Shared\Transfer\CmsSlotBlockConditionTransfer $cmsSlotBlockConditionTransfer */
        $cmsSlotBlockConditionTransfer = $cmsSlotBlockTransfer->getConditions()
            ->offsetGet(CmsSlotBlockProductCategoryConnectorConfig::CONDITION_KEY);

        if ($cmsSlotBlockConditionTransfer->getAll()) {
            return true;
        }

        return $cmsSlotParamsTransfer->getIdProductAbstract()
            && (
                in_array($cmsSlotParamsTransfer->getIdProductAbstract(), $cmsSlotBlockConditionTransfer->getProductIds())
                || $this->getIsProductInCategoryIds(
                    $cmsSlotParamsTransfer->getIdProductAbstract(),
                    $cmsSlotBlockConditionTransfer->getCategoryIds(),
                )
            );
    }

    /**
     * @param int $idProductAbstract
     * @param array<int> $conditionCategoryIds
     *
     * @return bool
     */
    protected function getIsProductInCategoryIds(int $idProductAbstract, array $conditionCategoryIds): bool
    {
        $productCategoryIds = $this->productCategoryReader->getAbstractProductCategoryIds($idProductAbstract);

        return count(array_intersect($conditionCategoryIds, $productCategoryIds)) > 0;
    }
}
