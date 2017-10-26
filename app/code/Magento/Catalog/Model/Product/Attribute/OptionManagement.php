<?php
/**
 * @author      Magento Core Team <core@magentocommerce.com>
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Model\Product\Attribute;

use Magento\Framework\Exception\InputException;

class OptionManagement implements \Magento\Catalog\Api\ProductAttributeOptionManagementInterface
{
    /**
     * @var \Magento\Eav\Api\AttributeOptionManagementInterface
     */
    protected $eavOptionManagement;

    /**
     * @param \Magento\Eav\Api\AttributeOptionManagementInterface $eavOptionManagement
     */
    public function __construct(
        \Magento\Eav\Api\AttributeOptionManagementInterface $eavOptionManagement
    ) {
        $this->eavOptionManagement = $eavOptionManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems($attributeCode)
    {
        return $this->eavOptionManagement->getItems(
            \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,
            $attributeCode
        );
    }

    /**
     * {@inheritdoc}
     */
    public function add($attributeCode, $option)
    {
        $currentOptions = $this->getItems($attributeCode);
        array_walk($currentOptions, function (&$attributeOption) {
            /** @var \Magento\Eav\Api\Data\AttributeOptionInterface $attributeOption */
            $attributeOption = $attributeOption->getLabel();
        });
        if (!in_array($option->getLabel(), $currentOptions)) {
            return $this->eavOptionManagement->add(
                \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,
                $attributeCode,
                $option
            );
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($attributeCode, $optionId)
    {
        if (empty($optionId)) {
            throw new InputException(__('Invalid option id %1', $optionId));
        }

        return $this->eavOptionManagement->delete(
            \Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,
            $attributeCode,
            $optionId
        );
    }
}
