<?php
require __DIR__ . '/vendor/autoload.php';

class SyncCalendar{

    
    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    
    public function listEventos($client){
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
            $listEvents=array();
            $i=0;
            foreach ($events as $event) {
                $i++;
                $myEvent=new stdClass();
                $start = $event->start->dateTime;
                $descr=$event->description;
                $myEvent->start=$start;
                $myEvent->descr=$descr;
                $myEvent->summary=$event->getSummary();
                //echo json_encode($myEvent->descr).'   '.$i.'    \n';
                array_push($listEvents,$myEvent);
            }
            return $listEvents;
        } else {
            $myEvents=array();
            return $myEvents;
        }
    }

    public function addEvent($client, $obj){
        // Refer to the PHP quickstart on how to setup the environment:
        // https://developers.google.com/calendar/quickstart/php
        // Change the scope to Google_Service_Calendar::CALENDAR and delete any stored
        // credentials.

        $ok=true;
  
        if($ok==true){
            $service = new Google_Service_Calendar($client);
            $event = new Google_Service_Calendar_Event(array(
                'summary' => 'Servicio: '.$obj->servicio,
                'description' => 'ID Reserva: '.$obj->id.',  Cliente: '.$obj->nombre.' '.$obj->apellido.',  Correo: '.$obj->email.',  Teléfono: '.$obj->telefono,
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
                array('email' => $obj->email),
                array('email' => 'killari.spa@gmail.com'),
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

            return ('ok');
        } 
    }
}

?>