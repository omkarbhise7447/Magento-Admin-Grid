<?php

namespace Rsgitech\Teams\Controller\Adminhtml\Allteams;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Rsgitech_Teams::teams_delete');
	}
	
	/**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('teams_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $department_namee = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Rsgitech\Teams\Model\Allteams::class);
                $model->load($id);
                $department_name = $model->getdepartmentName();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The teams has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_teams_on_delete',
                    ['department_name' => $department_name, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_teams_on_delete',
                    ['department_name' => $department_name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['teams_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a teams to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
