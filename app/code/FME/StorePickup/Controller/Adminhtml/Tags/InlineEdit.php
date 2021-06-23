<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Tags;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Cms\Api\Data\PageInterface;
use FME\StorePickup\Model\Tags as ModelTags;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $dataProcessor;
    protected $jsonFactory;
    protected $TagsModel;

    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        ModelTags $TagsModel,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->jsonFactory = $jsonFactory;
        $this->TagsModel = $TagsModel;
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $Id) {
            $Tags = $this->TagsModel->load($Id);
           
            try {
                $Data = $this->filterPost($postItems[$Id]);
                $this->validatePost($Data, $Tags, $error, $messages);
                $extendedPageData = $Tags->getData();
                $this->setStorePickupTagsData($Tags, $extendedPageData, $Data);
                $this->TagsModel->save($Tags);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPageId($Tags, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPageId($Tags, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPageId(
                    $Tags,
                    __('Something went wrong while saving the item.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function filterPost($postData = [])
    {
        $pageData = $this->dataProcessor->filter($postData);
        $pageData['custom_theme'] = isset($pageData['custom_theme']) ? $pageData['custom_theme'] : null;
        $pageData['custom_root_template'] = isset($pageData['custom_root_template'])
            ? $pageData['custom_root_template']
            : null;
        return $pageData;
    }
    
    protected function validatePost(
        array $pageData,
        \FME\StorePickup\Model\Tags $page,
        &$error,
        array &$messages
    ) {
        if (!($this->dataProcessor->validate($pageData) && $this->dataProcessor->validateRequireEntry($pageData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithPageId($page, $error->getText());
            }
        }
    }
     
    protected function getErrorWithPageId(ModelTags $page, $errorText)
    {
        return '[Page ID: ' . $page->getId() . '] ' . $errorText;
    }
      
    public function setStorePickupTagsData(
        ModelTags $page,
        array $extendedPageData,
        array $pageData
    ) {
        $page->setData(array_merge($page->getData(), $extendedPageData, $pageData));
        return $this;
    }
}
