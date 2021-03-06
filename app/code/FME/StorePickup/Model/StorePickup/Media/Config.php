<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace FME\StorePickup\Model\StorePickup\Media;

use Magento\Eav\Model\Entity\Attribute;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Catalog product media config
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Config implements ConfigInterface
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Attribute
     */
    private $attributeHelper;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * Filesystem directory path of product images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaPathAddition()
    {
        return 'StorePickup/storelocator/media';
    }

    /**
     * Web-based directory path of product images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaUrlAddition()
    {
        return 'StorePickup/storelocator/media';
    }

    /**
     * @return string
     */
    public function getBaseMediaPath()
    {
        return 'StorePickup/storelocator/';
    }

    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'tmp/StorePickup/storelocator/media';
    }

    /**
     * Filesystem directory path of temporary product images
     * relatively to media folder
     *
     * @return string
     */
    public function getBaseTmpMediaPath()
    {
        return 'tmp/' . $this->getBaseMediaPathAddition();
    }

    /**
     * @return string
     */
    public function getBaseTmpMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ) . 'tmp/' . $this->getBaseMediaUrlAddition();
    }

    /**
     * @param string $file
     * @return string
     */
    public function getMediaUrl($file)
    {
        return $this->getBaseMediaUrl() . '/' . $this->_prepareFile($file);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getMediaPath($file)
    {
        return $this->getBaseMediaPath() . '/' . $this->_prepareFile($file);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getTmpMediaUrl($file)
    {
        return $this->getBaseTmpMediaUrl() . '/' . $this->_prepareFile($file);
    }

    /**
     * Part of URL of temporary product images
     * relatively to media folder
     *
     * @param string $file
     * @return string
     */
    public function getTmpMediaShortUrl($file)
    {
        return 'tmp/' . $this->getBaseMediaUrlAddition() . '/' . $this->_prepareFile($file);
    }

    /**
     * Part of URL of product images relatively to media folder
     *
     * @param string $file
     * @return string
     */
    public function getMediaShortUrl($file)
    {
        return $this->getBaseMediaUrlAddition() . '/' . $this->_prepareFile($file);
    }

    /**
     * @param string $file
     * @return string
     */
    public function getTmpMediaPath($file)
    {
        return $this->getBaseTmpMediaPath() . '/' . $this->_prepareFile($file);
    }

    /**
     * @param string $file
     * @return string
     */
    protected function _prepareFile($file)
    {        
        return ltrim(str_replace('\\', '/', $file), '/');
    }

    /**
     * @return array
     */
    public function getMediaAttributeCodes()
    {
        return $this->getAttributeHelper()->getAttributeCodesByFrontendType('media_image');
    }

    /**
     * @return Attribute
     */
    private function getAttributeHelper()
    {
        if (null === $this->attributeHelper) {
            $this->attributeHelper = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Eav\Model\Entity\Attribute');
        }
        return $this->attributeHelper;
    }
}
