<?php

namespace Rsgitech\Member\Controller\Adminhtml\Allmember;

use Magento\Backend\App\Action;
use Rsgitech\Member\Model\Allmember;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Rsgitech\Member\Model\AllmemberFactory
     */
    private $allmemberFactory;

    /**
     * @var \Rsgitech\Member\Api\AllmemberRepositoryInterface
     */
    private $allmemberRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Rsgitech\Member\Model\AllmemberFactory $allmemberFactory
     * @param \Rsgitech\Member\Api\AllmemberRepositoryInterface $allmemberRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Rsgitech\Member\Model\AllmemberFactory $allmemberFactory = null,
        \Rsgitech\Member\Api\AllmemberRepositoryInterface $allmemberRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->allmemberFactory = $allmemberFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Rsgitech\Member\Model\AllmemberFactory::class);
        $this->allmemberRepository = $allmemberRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Rsgitech\Member\Api\AllmemberRepositoryInterface::class);
        parent::__construct($context);
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
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Allmember::STATUS_ENABLED;
            }
            if (empty($data['member_id'])) {
                $data['member_id'] = null;
            }

            /** @var \Rsgitech\Member\Model\Allmember $model */
            $model = $this->allmemberFactory->create();

            $id = $this->getRequest()->getParam('member_id');
            if ($id) {
                try {
                    $model = $this->allmemberRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This member no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'member_allmember_prepare_save',
                ['allmember' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->allmemberRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the member.'));
                $this->dataPersistor->clear('member_allmember');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['member_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the member.'));
            }

            $this->dataPersistor->set('member_allmember', $data);
            return $resultRedirect->setPath('*/*/edit', ['member_id' => $this->getRequest()->getParam('member_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
