<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector;

use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToStoreClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReader;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader\ProductCategoryReaderInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver\ProductCategoryCmsSlotBlockConditionResolver;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver\ProductCategoryCmsSlotBlockConditionResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotBlockProductCategoryConnectorFactory extends AbstractFactory
{
    public function createProductCategoryCmsSlotBlockConditionResolver(): ProductCategoryCmsSlotBlockConditionResolverInterface
    {
        return new ProductCategoryCmsSlotBlockConditionResolver(
            $this->createProductCategoryReader(),
        );
    }

    public function createProductCategoryReader(): ProductCategoryReaderInterface
    {
        return new ProductCategoryReader(
            $this->getLocaleClient(),
            $this->getProductCategoryStorageClient(),
            $this->getStoreClient(),
        );
    }

    public function getLocaleClient(): CmsSlotBlockProductCategoryConnectorToLocaleClientInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryConnectorDependencyProvider::CLIENT_LOCALE);
    }

    public function getProductCategoryStorageClient(): CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryConnectorDependencyProvider::CLIENT_PRODUCT_CATEGORY_STORAGE);
    }

    public function getStoreClient(): CmsSlotBlockProductCategoryConnectorToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockProductCategoryConnectorDependencyProvider::CLIENT_STORE);
    }
}
