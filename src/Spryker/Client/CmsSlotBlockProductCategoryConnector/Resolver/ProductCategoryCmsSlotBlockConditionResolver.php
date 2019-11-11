<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface;

class ProductCategoryCmsSlotBlockConditionResolver implements ProductCategoryCmsSlotBlockConditionResolverInterface
{
    /**
     * @uses \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm::FIELD_ALL
     */
    protected const CONDITIONS_DATA_KEY_ALL = 'all';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm::FIELD_PRODUCT_IDS
     */
    protected const CONDITIONS_DATA_KEY_PRODUCT_IDS = 'productIds';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockProductCategoryGui\Communication\Form\ProductCategorySlotBlockConditionForm::FIELD_CATEGORY_IDS
     */
    protected const CONDITIONS_DATA_KEY_CATEGORIES_IDS = 'categoryIds';

    protected const CONDITION_KEY = 'product';

    protected const SLOT_DATA_KEY_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @var \Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface
     */
    protected $productCategoryReader;

    /**
     * @param \Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface $productCategoryReader
     */
    public function __construct(ProductCategoryReaderInterface $productCategoryReader)
    {
        $this->productCategoryReader = $productCategoryReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    public function resolveIsSlotBlockConditionApplicable(CmsBlockTransfer $cmsBlockTransfer): bool
    {
        return isset($cmsBlockTransfer->getCmsSlotBlockConditions()[static::CONDITION_KEY]);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function resolveIsCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotData): bool
    {
        $conditionData = $cmsBlockTransfer->getCmsSlotBlockConditions()[static::CONDITION_KEY];

        if ($conditionData[static::CONDITIONS_DATA_KEY_ALL]) {
            return true;
        }

        $idProductAbstract = $cmsSlotData[static::SLOT_DATA_KEY_ID_PRODUCT_ABSTRACT] ?? null;

        if (!$idProductAbstract) {
            return false;
        }

        $idProductAbstract = (int)$idProductAbstract;

        if ($this->checkProductConditions($conditionData, $idProductAbstract)) {
            return true;
        }

        return $this->checkCategoryConditions($conditionData, $idProductAbstract);
    }

    /**
     * @param array $conditionData
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function checkProductConditions(array $conditionData, int $idProductAbstract): bool
    {
        return in_array($idProductAbstract, $conditionData[static::CONDITIONS_DATA_KEY_PRODUCT_IDS]);
    }

    /**
     * @param array $conditionData
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function checkCategoryConditions(array $conditionData, int $idProductAbstract): bool
    {
        $conditionDataCategoryIds = $conditionData[static::CONDITIONS_DATA_KEY_CATEGORIES_IDS];
        $productCategoryIds = $this->productCategoryReader->getAbstractProductCategoryIds($idProductAbstract);

        return count(array_intersect($conditionDataCategoryIds, $productCategoryIds)) > 0;
    }
}
