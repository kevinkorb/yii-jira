<?php
class Jira {
	protected $project;
	protected $host;
	protected $port;
	function __construct($config){
		$this->request = new RestRequest;
		$this->request->username = $config->username;
		$this->request->password = $config->password;
		$this->host = $config->host;
		$this->port = $config->port;
	}
	public function createIssue($json){
		$this->request->openConnect('http://'.$this->host.':'.$this->port.'/rest/api/latest/issue/', 'POST', $json);
		$this->request->execute();  
		echo '<pre>' . print_r($this->request, true) . '</pre>';
	}
    public function addAttachment($filename, $issueKey){
		$this->request->openConnect('http://'.$this->host.':'.$this->port.'/rest/api/latest/issue/'.$issueKey.'/attachments', 'POST', null, $filename);
		$this->request->execute();  
		echo '<pre>' . print_r($this->request, true) . '</pre>';
    }
	public function updateIssue($json, $issueKey){
		$this->request->openConnect('http://'.$this->host.':'.$this->port.'/rest/api/latest/issue/'.$issueKey, 'PUT', $json);
		$this->request->execute();  
		echo '<pre>' . print_r($this->request, true) . '</pre>';
	}
	public function getAvailableTransitions($issueKey)
	{
		$this->request->openConnect('http://'.$this->host.':'.$this->port.'/rest/api/latest/issue/'.$issueKey.'/transitions?expand=transitions.fields', 'GET');
		$this->request->execute();
		return $this->request->getParsedResponse();
	}
	public function postTransitions($issueKey, $json)
	{
		$this->request->openConnect('http://'.$this->host.':'.$this->port.'/rest/api/latest/issue/'.$issueKey.'/transitions', 'POST', $json);
		$this->request->execute();
		var_dump($this->request);
		return $this->request->getParsedResponse();
	}


	public function queryIssue($query){
		
		function createPairs($obj){
			$str = "";
			foreach ($obj as $key => $value) {
    			$str .= "$key=$value&";
			}
			return rtrim($str, '&');
		}
		$qs = createPairs($query);
		$this->request->OpenConnect('http://'.$this->host.':'.$this->port.'/rest/api/latest/search?jql='.$qs);
		$this->request->execute();  
		echo '<pre>' . print_r($this->request, true) . '</pre>';
	}
	
}
