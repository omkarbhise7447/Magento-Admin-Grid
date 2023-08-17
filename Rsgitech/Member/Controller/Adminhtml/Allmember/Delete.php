<?php

namespace Rsgitech\Member\Controller\Adminhtml\Allmember;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Rsgitech_Member::member_delete');
	}
	
	/**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('member_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $department_namee = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Rsgitech\Member\Model\Allmember::class);
                $model->load($id);
                $department_name = $model->getdepartmentName();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The member has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_member_on_delete',
                    ['department_name' => $department_name, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_member_on_delete',
                    ['department_name' => $department_name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['member_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a member to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
