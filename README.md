# WebhookTest

Application to test different Github Webhook Cases.

As I do not have an online server available I set this up on my local machine using ngrok.

Besides ngrok I used php and sendmail.

<b>sendmail.ini</b> needs to be edited to fit to your email provider.

<b>php.ini</b> needs to be adjusted as follows <br>ctrl+f "sendmail"  <br> add a leading ; to the lines: 

    ;SMTP = localhost 
    ;smtp_port = 25

and set the following two lines:

    sendmail_from = email@provider.de
    sendmail_path = "C:\YourPathToSendmailDir\sendmail.exe -t"


# Scheduling the mail service

    Open up Task Scheduler (Accessories > System Tools, or search for 'taskschd.msc')

    Create a task.

    Give it a name, Create a daylie trigger and specify a time to execute the batch.

    Now go to the Actions tab, hit New and Browse to the batch file that you just made, click OK and you're done.

    