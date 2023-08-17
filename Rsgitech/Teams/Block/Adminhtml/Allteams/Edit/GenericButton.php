<?php
namespace Rsgitech\Teams\Block\Adminhtml\Allteams\Edit;

use Magento\Backend\Block\Widget\Context;
use Rsgitech\Teams\Api\AllteamsRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;
   
    protected $allteamsRepository;
    
    public function __construct(
        Context $context,
        AllteamsRepositoryInterface $allteamsRepository
    ) {
        $this->context = $context;
        $this->allteamsRepository = $allteamsRepository;
    }

    public function getTeamsId()
    {
        try {
            return $this->allteamsRepository->getById(
                $this->context->getRequest()->getParam('teams_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
?>
