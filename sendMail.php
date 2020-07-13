<?php

if (file_exists('./tmp/events.json')) {
    $eventList = json_decode(file_get_contents('./tmp/events.json'));
    $output = "";
    foreach ($eventList as $eventObj) {
        foreach ($eventObj as $key => $value){
            $output .= "" . $key . ": " . $value . " | ";
        }
        $output = substr($output, 0, -2);
        $output .="\r\n";
    }
    $output = str_replace("\n.", "\n..", $output); //Caution (Windows only) When PHP is talking to a SMTP server directly, if a full stop is found on the start of a line, it is removed. To counter-act this, replace these occurrences with a double dot.
    sendmail($output);
}

function sendmail (string $output){
    $mailInfo = json_decode(file_get_contents('./mailInfo.json'));
    
    $to      = $mailInfo->mailTo;
    $subject = 'Github Webhook Test';
    $message = $output;
    $headers = "From: ".$mailInfo->mailFrom;

    $result = mail($to, $subject, $message, $headers);

    if($result){
        echo "Mail has been sent!";
        deleteJson();
    }
    else{
        echo "Failed to send Mail!";
        return;
    }
    
}

function deleteJson()
{
    unlink("./tmp/events.json");
}

