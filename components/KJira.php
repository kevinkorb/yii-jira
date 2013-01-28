<?php
class KJira extends CApplicationComponent
{

	public $port;
	public $domain;
	public $username;
	public $password;


	public function getInstance()
	{
		$config = new stdclass();
		$config->username= $this->username;
		$config->password= $this->password;
		$config->port= $this->port;
		$config->host= $this->domain;

		return new Jira($config);
	}

	public function queryIssue()
	{
		$query= new stdclass();
		$query->assignee = 'kevin';
		$query->project = 'DE';
		var_dump($this->getInstance()->queryIssue($query));
	}

	public function createIssue()
	{
		$data = new stdclass();
		@$data ->fields->project->key='DE';
		@$data ->fields->summary='testing';
		@$data ->fields->description="decription";
		@$data ->fields->issuetype->name='Bug';

		$return = $this->getInstance()->createIssue(
			$data
		);
		var_dump($return);
	}


}