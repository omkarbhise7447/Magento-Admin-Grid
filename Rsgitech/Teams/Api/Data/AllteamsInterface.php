<?php
namespace Rsgitech\Teams\Api\Data;

interface AllteamsInterface
{
	const TEAMS_ID = 'teams_id';
	const DEPARTMENT_NAME  = 'department_name';
	const CREATED_BY = 'created_by';
	const CREATED_AT = 'created_at';
	const UPDATED_BY = 'updated_by';
	const UPDATED_AT = 'updated_at';

	public function getteamsId();

	public function getdepartmentName();

	public function getCreatedBy();

	public function getCreatedAt();

    public function getUpdatedBy();

	public function getUpdatedAt();

	public function setteamsId($teams_id);

	public function setdepartmentName($department_name);

    public function setCreatedBy($created_by);

	public function setCreatedAt($created_at);

	public function setUpdatedBy($updated_by);

	public function setUpdatedAt($updated_at);
}
?>
