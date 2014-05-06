<?php
class KJira extends CApplicationComponent
{

	public $port;
	public $domain;
	public $username;
	public $password;

	protected $jira;

	/**
	 *
	 * return Jira
	 */
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

	public function queryIssue($jql, $maxResults = 50)
	{
		return $this->getInstance()
			->queryIssue($jql, $maxResults);
	}

	public function getLabelsForIssue($issueKey){
		$issueResponse = $this->getIssueByKey($issueKey);
		if(isset($issueResponse['fields']['labels'])){
			return $issueResponse['fields']['labels'];
		}
		return null;
	}

	public function getWorklog($startDate, $endDate, $targetGroup){
		return $this->getInstance()->getWorklog($startDate, $endDate, $targetGroup);
	}

	public function logTime($branch, $time, $comment){
		return $this->getInstance()->logTime($branch, $time, $comment);
	}

	public function getWorklogForIssue($issueIdOrKey){
		return $this->getInstance()->getWorklogForIssue($issueIdOrKey);
	}

	public function getAllProjects(){
		return $this->getInstance()->getAllProjects();
	}

	public function getProjectByKey($projectIdOrKey)
	{
		return $this->getInstance()->getProjectByKey($projectIdOrKey);
	}

	public function getTimesheet(){
		return $this->getInstance()->getTimesheet();
	}

	public function getIssueByKey($key){
		return $this->getInstance()->getIssueByKey($key);
	}

	public function addComment($key, $comment){
		$this->getInstance()->addComment($key, $comment);
	}

	public function getAllSprints(){
		return $this->getInstance()->getAllSprints();
	}

	public function getRepidViewBacklogById($id){
		return $this->getInstance()->getRepidViewBacklogById($id);
	}

	public function changeStatus($key, $status_text)
	{
		$transitions = $this->getAvailableTransitions($key);
//		var_dump($transitions);
		foreach($transitions['transitions'] AS $transition)
		{
			if($transition['name'] == $status_text)
			{
//				var_dump($transition);
				$this->setTransition($key, $transition['id']);
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
//		var_dump($record);
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