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
