<?php
namespace Rsgitech\Member\Block\Adminhtml\Allmember\Edit;

use Magento\Backend\Block\Widget\Context;
use Rsgitech\Member\Api\AllmemberRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;
   
    protected $allmemberRepository;
    
    public function __construct(
        Context $context,
        AllmemberRepositoryInterface $allmemberRepository
    ) {
        $this->context = $context;
        $this->allmemberRepository = $allmemberRepository;
    }

    public function getMemberId()
    {
        try {
            return $this->allmemberRepository->getById(
                $this->context->getRequest()->getParam('member_id')
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
