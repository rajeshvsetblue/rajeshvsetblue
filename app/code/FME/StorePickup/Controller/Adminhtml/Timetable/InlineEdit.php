<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\StorePickup\Controller\Adminhtml\Timetable;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Cms\Api\Data\PageInterface;
use FME\StorePickup\Model\Timetable as ModelTimetable;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $dataProcessor;
    protected $jsonFactory;
    protected $TimetableModel;

    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        ModelTimetable $TimetableModel,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->jsonFactory = $jsonFactory;
        $this->TimetableModel = $TimetableModel;
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
            $Timetable = $this->TimetableModel->load($Id);
           
            try {
                $Data = $this->filterPost($postItems[$Id]);
                $this->validatePost($Data, $Timetable, $error, $messages);
                $extendedPageData = $Timetable->getData();
                $this->setStorePickupTimetableData($Timetable, $extendedPageData, $Data);
                $this->TimetableModel->save($Timetable);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPageId($Timetable, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPageId($Timetable, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPageId(
                    $Timetable,
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
        \FME\StorePickup\Model\Timetable $page,
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
     
    protected function getErrorWithPageId(ModelTimetable $page, $errorText)
    {
        return '[Page ID: ' . $page->getId() . '] ' . $errorText;
    }
      
    public function setStorePickupTimetableData(
        ModelTimetable $page,
        array $extendedPageData,
        array $pageData
    ) {
        $page->setData(array_merge($page->getData(), $extendedPageData, $pageData));
        return $this;
    }
}
