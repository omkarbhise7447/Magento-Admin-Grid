<?php
namespace Rsgitech\Member\Controller\Adminhtml\Allmember;

use Magento\Backend\App\Action\Context;
use Rsgitech\Member\Api\AllmemberRepositoryInterface as AllmemberRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Rsgitech\Member\Api\Data\AllmemberInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $allmemberRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        Context $context,
        AllmemberRepository $allmemberRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->allmemberRepository = $allmemberRepository;
        $this->jsonFactory = $jsonFactory;
    }
	
	/**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Rsgitech_Member::save');
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

        foreach (array_keys($postItems) as $memberId) {
            $member = $this->allmemberRepository->getById($memberId);
            try {
                $memberData = $postItems[$memberId];
                $extendedMemberData = $member->getData();
                $this->setMemberData($member, $extendedMemberData, $memberData);
                $this->allmemberRepository->save($member);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithMemberId($member, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithMemberId($member, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithMemberId(
                    $member,
                    __('Something went wrong while saving the member.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithMemberId(AllmemberInterface $member, $errorText)
    {
        return '[Member ID: ' . $member->getmemberId() . '] ' . $errorText;
    }

    public function setMemberData(\Rsgitech\Member\Model\Allmember $member, array $extendedmemberData, array $memberData)
    {
        $member->setData(array_merge($member->getData(), $extendedmemberData, $memberData));
        return $this;
    }
}
