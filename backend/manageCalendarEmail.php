<?php

require_once('./libs/googlecalendar/quickstart.php');
require_once('Db.php');
require_once('./libs/googlecalendar/quickstartconsole.php');
require_once('./libs/phpmailer/emailKillari.php');



class GoogleApiAndEmailManager{

    function syncCalendar($obj, $client){
        $calendar=new SyncCalendar();
        $events=$calendar->addEvent($client, $obj);
        $this->deleteEvent($obj->id);
    }

    function getCalendarList($client){
        $calendar=new SyncCalendar();
        $events=$calendar->listEventos($client);
        return $events;
    }

    function deleteEvent($id){
        $db=Db::conectar();
        $select=$db->prepare('DELETE FROM reservas WHERE id_reserva=:id');
        $select->bindValue('id',$id);
        $select->execute();
    }

    function getDbData(){
        date_default_timezone_set("America/Guayaquil");
        $fecha=date("Y-m-d");
        $hora=date("h:i");
        $date=new DateTime($fecha);
        $date->modify("+5 day");
        $fechafutura=$date->format("Y-m-d");
        //print(json_encode($fecha.' '.$fechafutura));
        $listaReservas=array();
        $db=Db::conectar();
        $select=$db->prepare('SELECT * FROM reservas WHERE fecha>=:fecha AND hora>=:hora AND fecha<=:fechafutura');
        $select->bindValue('fecha',$fecha);
        $select->bindValue('hora',$hora);
        $select->bindValue('fechafutura',$fechafutura);
        $select->execute();
        foreach($select->fetchAll() as $cita){
            $myCita=new stdClass();
            $myCita->id=$cita['id_reserva'];
            $myCita->servicio=$cita['servicio'];
            $myCita->fecha=$cita['fecha'];
            $myCita->hora=$cita['hora'];
            $myCita->nombre=$cita['nombre'];
            $myCita->apellido=$cita['apellido'];
            $myCita->email=$cita['email'];
            $myCita->telefono=$cita['telefono'];
            array_push($listaReservas, $myCita);
        }
        return (($listaReservas));
    }

    function getClient(){
        $cliente=new Client();
        $client=$cliente->getClient();
        $ok=$cliente->getToken($client);
        //echo $ok;

        if($ok=='ok'){
            echo 'este es ok: '.$ok;
            return $client;
        }elseif(!empty($ok)){
            //echo 'este no es ok:'.$ok;
            $mail=new Email();
            $mail->sendEmail('http://www.killari.com.ec/reservaciones/backend/', $ok);
        }else{
            //echo 'ok vacio';
            return $client;
        }
        return $client;
    }

    function receive($code){
        $cliente=new Client();
        $client=$cliente->getClient();
        $cliente->receiveKey($client, $code);
    }

}

$client=new GoogleApiAndEmailManager();
if(isset($_POST['code']) && $_POST['code']!=null){
    $client->receive($_POST['code']);
}
$obj=$client->getDbData();
$lista=$client->getCalendarList($client->getClient());
//echo json_encode($lista);

    if(!empty($obj)){
        foreach($obj as $objeto){
            $is=true;
            $time = strtotime($objeto->fecha.' '.$objeto->hora);
            $newformat = date('Y-m-d'.'\T'.'H:i:s',$time);
            $start=$newformat.'-05:00';
            $end = date('Y-m-d\TH:i:s',strtotime('+1 hour',strtotime($newformat))).'-05:00';
            $objeto->start=$start;
            $objeto->end=$end;
            //echo $lista[$i]->start.'  otro: '.$objeto->start;
            for($i=0; $i<count($lista); $i++){

                $arr=explode(',', $lista[$i]->descr, 2);
                $arr2=explode(': ',$arr[0], 2);
                $id=$arr2[1];
                //echo json_encode($arr2[1]);

                if($lista[$i]->start==$objeto->start && $objeto->id==$id){
                    $is=false;
                }
            }
            if($is==true){
                $client->syncCalendar($objeto, $client->getClient());
            }
        }
    }



?>