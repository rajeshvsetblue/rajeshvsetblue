<?php

namespace FME\StorePickup\Block\Adminhtml\Storelocator\Edit\Tab;

use Magento\Backend\Block\Media\Uploader;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\App\Filesystem\DirectoryList;

class Image extends \Magento\Backend\Block\Widget
{

    protected $_template = 'storelocator/media/helper/gallery.phtml';
    protected $_mediaConfig;
    protected $_jsonEncoder;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \FME\StorePickup\Model\StorePickup\Media\Config $mediaConfig,
        array $data = []
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        $this->_mediaConfig = $mediaConfig;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $this->addChild('uploader', 'Magento\Backend\Block\Media\Uploader');

        $this->getUploader()->getConfig()->setUrl(
            $this->_urlBuilder->addSessionParam()->getUrl('StorePickup/media/upload')
        )->setFileField(
            'image'
        )->setFilters(
            [
            'images' => [
                'label' => __('Images (.gif, .jpg, .png)'),
                'files' => ['*.gif', '*.jpg', '*.jpeg', '*.png'],
            ],
            ]
        );

        $this->_eventManager->dispatch('media_product_gallery_prepare_layout', ['block' => $this]);
        return parent::_prepareLayout();
    }

    public function getUploader()
    {
        return $this->getChildBlock('uploader');
    }


    public function getUploaderHtml()
    {
        return $this->getChildHtml('uploader');
    }

    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }

    public function getAddImagesButton()
    {
        return $this->getButtonHtml(
            __('Add New Images'),
            $this->getJsObjectName() . '.showUploader()',
            'add',
            $this->getHtmlId() . '_add_images_button'
        );
    }

    public function getImagesJson()
    {

        $value = $this->getElement()->getImages();
        if (is_array($value) &&
        array_key_exists('images', $value) &&
        is_array($value['images']) &&
        count($value['images'])
        ) {
            $directory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $images = $this->sortImagesByPosition($value['images']);
            foreach ($images as &$image) {
                $image['url'] = $this->_mediaConfig->getMediaUrl($image['file']);
                $fileHandler = $directory->stat($this->_mediaConfig->getMediaPath($image['file']));
                $image['size'] = $fileHandler['size'];
            }
            return $this->_jsonEncoder->encode($images);
        }
        return '[]';
    }

    private function sortImagesByPosition($images)
    {
        if (is_array($images)) {
            usort($images, function ($imageA, $imageB) {
                return ($imageA['position'] < $imageB['position']) ? -1 : 1;
            });
        }
        return $images;
    }

    public function getImagesValuesJson()
    {
        $values = [];
        foreach ($this->getMediaAttributes() as $attribute) {
            /* @var $attribute \Magento\Eav\Model\Entity\Attribute */
            $values[$attribute->getAttributeCode()] = $this->getElement()->getDataObject()->getData(
                $attribute->getAttributeCode()
            );
        }
        return $this->_jsonEncoder->encode($values);
    }

    public function getImageTypes()
    {
        $imageTypes = [];
        foreach ($this->getMediaAttributes() as $attribute) {
            /* @var $attribute \Magento\Eav\Model\Entity\Attribute */
            $imageTypes[$attribute->getAttributeCode()] = [
            'code' => $attribute->getAttributeCode(),
            'value' => $this->getElement()->getDataObject()->getData($attribute->getAttributeCode()),
            'label' => $attribute->getFrontend()->getLabel(),
            'scope' => __($this->getElement()->getScopeLabel($attribute)),
            'name' => $this->getElement()->getAttributeFieldName($attribute),
            ];
        }
        return $imageTypes;
    }

    public function hasUseDefault()
    {
        foreach ($this->getMediaAttributes() as $attribute) {
            if ($this->getElement()->canDisplayUseDefault($attribute)) {
                return true;
            }
        }

        return false;
    }

    public function getMediaAttributes()
    {
        return $this->getElement()->getDataObject()->getMediaAttributes();
    }
    
    public function getImageTypesJson()
    {
        return $this->_jsonEncoder->encode($this->getImageTypes());
    }
}
