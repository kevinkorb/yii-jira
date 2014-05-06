<?php
class Jira {

	protected $project;
	protected $host;
	protected $port;
	protected $request;
	function __construct($config){
		$this->request = new RestRequest;
		$this->request->username = $config->username;
		$this->request->password = $config->password;
		$this->host = $config->host;
		$this->port = $config->port;
	}
	public function createIssue($json){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/', 'POST', $json);
		$this->request->execute();  
		echo '<pre>' . print_r($this->request, true) . '</pre>';
	}
    public function addAttachment($filename, $issueKey){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/'.$issueKey.'/attachments', 'POST', null, $filename);
		$this->request->execute();  
		echo '<pre>' . print_r($this->request, true) . '</pre>';
    }
	public function updateIssue($json, $issueKey){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/'.$issueKey, 'PUT', $json);
		$this->request->execute();  
		echo '<pre>' . print_r($this->request, true) . '</pre>';
	}

	public function getIssueByKey($issueKey){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/'.$issueKey, 'GET');
		$this->request->execute();
		return $this->request->getParsedResponse();
	}

	public function getMyself(){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/myself', 'GET');
		$this->request->execute();
		echo '<pre>' . print_r($this->request, true) . '</pre>';
		return $this->request;

	}

	public function logTime($branch, $time, $comment = ''){
		$Myself = $this->getMyself();

		$ResponseBody = json_decode($Myself->getResponseBody());
		$obj = new stdClass();
		$obj->author = new stdClass();
		$obj->author->self =$ResponseBody->self;
		$obj->author->name = $ResponseBody->name;
		$obj->author->displayName = $ResponseBody->displayName;
		$obj->author->active = true;

		$obj->updateAuthor = new stdClass();
		$obj->updateAuthor->self =$ResponseBody->self;
		$obj->updateAuthor->name = $ResponseBody->name;
		$obj->updateAuthor->displayName = $ResponseBody->displayName;
		$obj->updateAuthor->active = true;

		$obj->comment = $comment;
		$obj->timeSpent = $time;
		$obj->started = null;


//		var_dump($obj);

		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/' . $branch."/worklog", 'POST', $obj);
		$this->request->execute();
//		echo '<pre>' . print_r($this->request, true) . '</pre>';
	}

	public function addComment($key, $comment){
		$json = array('body' => $comment);
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/'.$key.'/comment', 'POST', $json);
		$this->request->execute();
//		echo '<pre>' . print_r($this->request, true) . '</pre>';
		return $this->request->getParsedResponse();
	}

	public function getAvailableTransitions($issueKey)
	{
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/'.$issueKey.'/transitions?expand=transitions.fields', 'GET');
		$this->request->execute();
		return $this->request->getParsedResponse();
	}
	public function postTransitions($issueKey, $json)
	{
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/issue/'.$issueKey.'/transitions', 'POST', $json);
		$this->request->execute();
		return $this->request->getParsedResponse();
	}

	public function getRepidViewBacklogById($id){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/greenhopper/1.0/xboard/plan/backlog/data.json?rapidViewId=' . $id);
		$this->request->execute();
//		KK_Debug::output_array($this->request);
//		die();
		return $this->request->getParsedResponse();
	}

	public function getAllSprints(){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/greenhopper/1.0/rapidview');
		$this->request->execute();
		return $this->request->getParsedResponse();
	}

	public function getSprintById($id){
		$this->request->openConnect('https://'.$this->host.':'.$this->port.'/rest/greenhopper/1.0/sprints/'.$id);
		$this->request->execute();
		return $this->request->getParsedResponse();
	}

	public function queryIssue($jql, $maxResults = 50){
		$this->request->OpenConnect('https://'.$this->host.':'.$this->port.'/rest/api/2/search?jql='. $jql . "&maxResults=$maxResults");
		$this->request->execute();
//		KK_Debug::output_array($this->request);
//		die();
		return $this->request->getParsedResponse();
	}

	public function getProjectByKey($projectIdOrKey){
		$this->request->OpenConnect($url = 'https://'.$this->host.':'.$this->port.'/rest/api/2/project/'.$projectIdOrKey);
		$this->request->execute();
		return $this->request->getParsedResponse();
	}

	public function getWorklog($startDate, $endDate, $targetGroup){
		$this->request->OpenConnect($url = 'https://'.$this->host.':'.$this->port."/rest/timesheet-gadget/1.0/raw-timesheet.json?startDate={$startDate}&endDate={$endDate}&targetGroup=$targetGroup");
		$this->request->execute();
		return $this->request->getParsedResponse();

	}

	public function getWorklogForIssue($issueIdOrKey){
		$this->request->OpenConnect($url = 'https://'.$this->host.':'.$this->port.'/rest/api/2/issue/'.$issueIdOrKey.'/worklog');
		$this->request->execute();
		return $this->request->getParsedResponse();
	}


	public function getAllProjects(){
		$this->request->OpenConnect('https://'.$this->host.':'.$this->port."/rest/api/2/project");
		$this->request->execute();
		return $this->request->getParsedResponse();

	}
	
}
