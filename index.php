<?
//Config
$username = "Westbomke";
$subscribedEvents = array("opened", "unsignedCommit", "newTag");
//Config end

try	{
    if($_POST)	{
			// Header Array
			$webhookEventHeader = array();
			foreach ($_SERVER as $key => $value) {
				if (strpos($key, 'HTTP_') === 0) {
						$webhookEventHeader[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
				}
			}

			//check event type of header for subscribed events
			if($webhookEventHeader["XGithubEvent"] === "push")
			{
				
			}

			//Payload
			$webhookEventData = json_decode($_POST["payload"]);

			//Logs for debugging
			file_put_contents('./tmp/ngroktest.log', ($_POST["payload"]));

			file_put_contents('./tmp/formatedOutputTest.log', ($webhookEventData->sender->login));
			
			var_dump($webhookEventHeader);


    }
    else {
        throw new Exception("post request empty", 1); 
    }
} catch (\Throwable $th) {
    file_put_contents('./tmp/error.log', $th . "__" . date("F j, Y, g:i a"));
}
