<?php
namespace Rsgitech\Member\Api;

interface AllmemberRepositoryInterface
{
	public function save(\Rsgitech\Member\Api\Data\AllmemberInterface $member);

    public function getById($memberId);

    public function delete(\Rsgitech\Member\Api\Data\AllmemberInterface $member);

    public function deleteById($memberId);
}
?>