<?php
namespace Rsgitech\Member\Api\Data;

interface AllmemberInterface
{
	const MEMBER_ID = 'member_id';
	const NAME  = 'name';
	const DEPARTMENT  = 'department';
	const PHOTO = 'photo';
	const TITLE = 'title';
	const QUOTES = 'quotes';

	public function getmemberId();

	public function getName();

	public function getDepartment();
	
	public function getPhoto();

	public function getTitle();

    public function getQuotes();

	public function setmemberId($member_id);

	public function setName($name);

	public function setDepartment($department);

    public function setPhoto($photo);

	public function setTitle($title);

	public function setQuotes($quotes);
}
?>
