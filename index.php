<?php
$username = "Westbomke";
$subscribedEvents = array("pull_request", "release", "unsignedCommit"); 
//pull_request = pull request, release = new tag, (unsigned commit not implemented yet), 
//Config end

getPostRequest($username, $subscribedEvents);

//Checks Post Request object and Header 
function getPostRequest(string $username, array $subscribedEvents){
	try	{
		if ($_POST)	{
			
			// Header Array - We need this to reliably check and differentiate the type of Event we receive
			$webhookEventHeader = array();
			foreach ($_SERVER as $key => $value) {
				if (strpos($key, 'HTTP_') === 0) {
						$webhookEventHeader[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
				}
			}

			//Payload object - Contains a bunch of information about the Github Event.
			$webhookEventData = json_decode($_POST["payload"]);
			isSubscribedEvent($webhookEventHeader, $webhookEventData, $username, $subscribedEvents);

		}
		else {
			throw new Exception("post request empty", 1); 
		}
	} catch (\Throwable $th) {
		file_put_contents('./tmp/error.log', $th . "__" . date("F j, Y, g:i a"));
	}
}

//Check if the triggered Event is supposed to be tracked / logged.
function isSubscribedEvent(array $webhookEventHeader, object $webhookEventData, string $username, array $subscribedEvents)
{
	//If the event was caused by yourself (defined at the top), ignore it.
	//Important: This case is inverted so it can be tested without having to create a second account.
	if($webhookEventData->sender->login !== $username) 
	{
		return;
	}

	$activeEvents = array();
	foreach ($subscribedEvents as $eventName) {
		if($webhookEventHeader["XGithubEvent"] === $eventName)
		{
			array_push($activeEvents, $eventName);
		}
	}

	if (count($activeEvents) > 0) {
		buildEventObject($activeEvents, $webhookEventData);
	}
	
}

//Builds an Object containing only relevant data.
function buildEventObject(array $activeEvents, object $webhookEventData){
	date_default_timezone_set('Europe/Berlin');
	$date = date('d/m/Y G:i:s', time());

	$eventObject = new \stdClass();
	$eventObject->Timestamp = $date;
	$eventObject->EventType = implode(",", $activeEvents);
	$eventObject->Username = $webhookEventData->sender->login;

	addToEventList($eventObject);
}

//Check if there are any previous Events logged and append to those if thats the case.
function addToEventList(object $eventObject)
{
	if (file_exists('./tmp/events.json')) {
		$eventList = json_decode(file_get_contents('./tmp/events.json'));
	}
	else {
		$eventList = array();
	}
	array_push($eventList, $eventObject);
	file_put_contents('./tmp/events.json', (json_encode($eventList)));
}

