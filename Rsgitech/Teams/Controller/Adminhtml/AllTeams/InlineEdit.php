<?php
namespace Rsgitech\Teams\Controller\Adminhtml\Allteams;

use Magento\Backend\App\Action\Context;
use Rsgitech\Teams\Api\AllteamsRepositoryInterface as AllteamsRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Rsgitech\Teams\Api\Data\AllteamsInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $allteamsRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        Context $context,
        AllteamsRepository $allteamsRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->allteamsRepository = $allteamsRepository;
        $this->jsonFactory = $jsonFactory;
    }
	
	/**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Rsgitech_Teams::save');
	}

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
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

        foreach (array_keys($postItems) as $teamsId) {
            $teams = $this->allteamsRepository->getById($teamsId);
            try {
                $teamsData = $postItems[$teamsId];
                $extendedTeamsData = $teams->getData();
                $this->setTeamsData($teams, $extendedTeamsData, $teamsData);
                $this->allteamsRepository->save($teams);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithTeamsId($teams, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithTeamsId($teams, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithTeamsId(
                    $teams,
                    __('Something went wrong while saving the teams.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithTeamsId(AllteamsInterface $teams, $errorText)
    {
        return '[Teams ID: ' . $teams->getteamsId() . '] ' . $errorText;
    }

    public function setTeamsData(\Rsgitech\Teams\Model\Allteams $teams, array $extendedTeamsData, array $teamsData)
    {
        $teams->setData(array_merge($teams->getData(), $extendedTeamsData, $teamsData));
        return $this;
    }
}
