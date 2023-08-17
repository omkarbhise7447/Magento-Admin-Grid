<?php
namespace Rsgitech\Teams\Block\Adminhtml;

class Allteams extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_allteams';
        $this->_blockGroup = 'Rsgitech_Teams';
        $this->_headerText = __('Manage Teams');

        parent::_construct();

        if ($this->_isAllowedAction('Rsgitech_Teams::save')) {
            $this->buttonList->update('add', 'label', __('Add Teams'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
?>
