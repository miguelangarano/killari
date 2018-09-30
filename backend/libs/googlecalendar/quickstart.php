<?php
require __DIR__ . '/vendor/autoload.php';
//require_once(__DIR__.'/credentials.json');

class SyncCalendar{

    
    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    public function getClient()
    {
        
        $client = new Google_Client();
        $client->setApplicationName('Google Calendar API PHP for Killari');
        $client->setScopes(Google_Service_Calendar::CALENDAR);

        //$cred=new Credentials();
        $client->setAuthConfig(__DIR__.'\client_secret.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        $tokenPath = __DIR__.'\token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                //Request authorization to user for new token
                echo 'User authorization is needed to generate new token';
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }


    public function listEvents($client){
         // Get the API client and construct the service object.
        //$client = getClient();
        $service = new Google_Service_Calendar($client);

        // Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
        'maxResults' => 100,
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        if (!empty($events)) {
            $myEvent=new stdClass();
            $listEvents=array();
            foreach ($events as $event) {
                $myEvent=new stdClass();
                $start = $event->start->dateTime;
                $myEvent->start=$start;
                $myEvent->summary=$event->getSummary();
                array_push($listEvents,$myEvent);
            }
            return $listEvents;
        } else {
            $myEvents=[];
            return $myEvents;
        }
    }


   

    public function addEvent($client, $obj){
        // Refer to the PHP quickstart on how to setup the environment:
        // https://developers.google.com/calendar/quickstart/php
        // Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
        // credentials.

        $ok=true;

        $lista=$this->listEvents($client);

        /*for($i=0; $i<count($lista); $i++){
            if($lista[$i]->start==$obj->start){
                $ok=false;
            }
        }*/

        
        if($ok==true){
            $service = new Google_Service_Calendar($client);
            $event = new Google_Service_Calendar_Event(array(
                'summary' => 'Servicio: '.$obj->servicio,
                'description' => 'ID Reserva: '.$obj->id.'Cliente: '.$obj->nombre.', Correo: '.$obj->correo.', Teléfono: '.$obj->numero,
                'start' => array(
                'dateTime' => $obj->start,
                'timeZone' => 'America/Guayaquil',
                ),
                'end' => array(
                'dateTime' => $obj->end,
                'timeZone' => 'America/Guayaquil',
                ),
                'recurrence' => array(
                'RRULE:FREQ=DAILY;COUNT=1'
                ),
                'attendees' => array(
                array('email' => $obj->correo),
                array('email' => 'centroderelajacion@killari.com.ec'),
                ),
                'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                ),
                ),
            ));
            
            $calendarId = 'primary';
            $event = $service->events->insert($calendarId, $event);

            return 'ok';
        }else{
            return 'error';
        }   
    }
}