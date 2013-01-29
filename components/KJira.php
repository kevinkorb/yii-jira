<?php
class KJira extends CApplicationComponent
{

	public $port;
	public $domain;
	public $username;
	public $password;

	protected $jira;

	public function getInstance()
	{
		if($this->jira)
		{
			return $this->jira;
		}
		$config = new stdclass();
		$config->username= $this->username;
		$config->password= $this->password;
		$config->port= $this->port;
		$config->host= $this->domain;

		$this->jira = new Jira($config);
		return $this->jira;
	}

	public function queryIssue()
	{
		$query= new stdclass();
		$query->assignee = 'kevin';
		$query->project = 'DE';
		var_dump($this->getInstance()->queryIssue($query));
	}

	public function changeStatus($key, $status_text)
	{
		$transitions = $this->getAvailableTransitions($key);
		var_dump($transitions);
		foreach($transitions['transitions'] AS $transition)
		{
			if($transition['name'] == $status_text)
			{
				var_dump($transition)
;				$this->setTransition($key, $transition['id']);
			}
		}
	}

	public function setTransition($key, $transition_id, $comment = null)
	{
		$record = new stdclass();
		$comment = new stdclass();
		@$comment->add->body = "Moved into PR";
		@$record->update->comment = array(
			$comment
		);
		@$record->transition->id = $transition_id;
		var_dump($record);
		return $this->getInstance()->postTransitions($key, $record);
	}

	public function getAvailableTransitions($key)
	{
		return $this->getInstance()->getAvailableTransitions($key);
	}

	public function editIssue($key, $json)
	{
		$ret = $this->getInstance()->updateIssue($json, $key);
		return $ret;
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