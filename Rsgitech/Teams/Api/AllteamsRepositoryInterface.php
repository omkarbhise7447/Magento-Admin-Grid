<?php
namespace Rsgitech\Teams\Api;

interface AllteamsRepositoryInterface
{
	public function save(\Rsgitech\Teams\Api\Data\AllteamsInterface $teams);

    public function getById($teamsId);

    public function delete(\Rsgitech\Teams\Api\Data\AllteamsInterface $teams);

    public function deleteById($teamsId);
}
?>