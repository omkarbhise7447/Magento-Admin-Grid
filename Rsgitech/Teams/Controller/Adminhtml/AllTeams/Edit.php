<?php

namespace Rsgitech\Teams\Controller\Adminhtml\Allteams;

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
		return $this->_authorization->isAllowed('Rsgitech_Teams::save');
	}

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Allteams
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Allteams $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rsgitech_Teams::teams_allteams')
            ->addBreadcrumb(__('Teams'), __('Teams'))
            ->addBreadcrumb(__('Manage All Teams'), __('Manage All Teams'));
        return $resultPage;
    }

    /**
     * Edit Allteams
     *
     * @return \Magento\Backend\Model\View\Result\Allteams|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('teams_id');
        $model = $this->_objectManager->create(\Rsgitech\Teams\Model\Allteams::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This teams no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('teams_allteams', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Allteams $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Teams') : __('Add Teams'),
            $id ? __('Edit Teams') : __('Add Teams')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Allteams'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add Teams'));

        return $resultPage;
    }
}
