<?php

namespace Rsgitech\Member\Controller\Adminhtml\Allmember;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
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
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Allmember
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Allmember $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rsgitech_Member::member_allmember')
            ->addBreadcrumb(__('Member'), __('Member'))
            ->addBreadcrumb(__('Manage All Member'), __('Manage All Member'));
        return $resultPage;
    }

    /**
     * Edit Allmember
     *
     * @return \Magento\Backend\Model\View\Result\Allmember|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('member_id');
        $model = $this->_objectManager->create(\Rsgitech\Member\Model\Allmember::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This member no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('member_allmember', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Allmember $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Member') : __('Add Member'),
            $id ? __('Edit Member') : __('Add Member')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Allmember'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add Member'));

        return $resultPage;
    }
}
