<?php

header('Access-Control-Allow-Origin: *');
require_once('./libs/phpmailer/email.php');
require_once('./libs/googlecalendar/quickstart.php');
require_once('Db.php');


class Data{

    //public $proxy='http://www.killari.com.ec/wp-content/themes/wellnesscenter/assets/js/';
    public $proxy='http://localhost/killa/';

    public function saveData($obj){
        $db=Db::conectar();
        $insert=$db->prepare('INSERT INTO reservas VALUES(NULL, :servicio, :fecha, :hora, :nombre, :direccion, :ciudad, :numero, :correo, :obs, :news  )');
        $insert->bindValue('servicio',$obj->servicio);
        $insert->bindValue('fecha',$obj->fecha);
        $insert->bindValue('hora',$obj->hora);
        $insert->bindValue('nombre',$obj->nombre);
        $insert->bindValue('direccion',$obj->direccion);
        $insert->bindValue('ciudad',$obj->ciudad);
        $insert->bindValue('numero',$obj->numero);
        $insert->bindValue('correo',$obj->correo);
        $insert->bindValue('obs',$obj->obs);
        $insert->bindValue('news',$obj->news);
        $insert->execute();
        $LAST_ID = $db->lastInsertId();
		return $LAST_ID;
    }

    public function saveHistoryData($obj){
        $db=Db::conectar();
        $insert=$db->prepare('INSERT INTO historiareservas VALUES(NULL, :id_reserva, :servicio, :fecha, :hora, :nombre, :direccion, :ciudad, :numero, :correo, :obs, :news  )');
        $insert->bindValue('id_reserva',$obj->id);
        $insert->bindValue('servicio',$obj->servicio);
        $insert->bindValue('fecha',$obj->fecha);
        $insert->bindValue('hora',$obj->hora);
        $insert->bindValue('nombre',$obj->nombre);
        $insert->bindValue('direccion',$obj->direccion);
        $insert->bindValue('ciudad',$obj->ciudad);
        $insert->bindValue('numero',$obj->numero);
        $insert->bindValue('correo',$obj->correo);
        $insert->bindValue('obs',$obj->obs);
        $insert->bindValue('news',$obj->news);
        $insert->execute();
        $LAST_ID = $db->lastInsertId();
		return $LAST_ID;
    }

    public function getData($obj){
        $db=Db::conectar();
        $select=$db->prepare('SELECT * FROM reservas WHERE fecha=:fecha AND hora=:hora');
        $select->bindValue('fecha',$obj->fecha);
        $select->bindValue('hora',$obj->hora);
        $select->execute();
        $cita=$select->fetch();
        $id=$cita['id_reserva'];
        return $id;
    }


    public function sendEmail($obj){
        $email=new Email();
        $email->sendEmail($this->proxy, $obj, 1);
        //$email->sendEmail('centroderelajacion@killari.com.ec', $this->proxy, $obj->nombre, $obj->fecha, $obj->hora, $obj->servicio, 0);
    }


    public function syncCalendar($obj){
        $db=Db::conectar();
        $select=$db->prepare('SELECT vecess FROM veces');
        $select->execute();
        $times=$select->fetch();
        $veces=$times['vecess'];

        if($veces==''){
            $veces=1;
            $insert=$db->prepare('INSERT INTO veces VALUES(NULL, :vecess  )');
            $insert->bindValue('vecess',$veces);
            $insert->execute();
        }else{
            $veces++;
            $aid=$db->lastInsertId();
            echo 'v: '.$veces.'<br/>';
            $insert=$db->prepare('UPDATE veces SET vecess=:veces WHERE id_times=3 ');
            $insert->bindValue('veces',$veces);
            //$insert->bindValue('id',$aid);
            $insert->execute();
        }
        
        
        $calendar=new SyncCalendar();
        $client=$calendar->getClient();
        $events=$calendar->addEvent($client, $obj);
        //$events=$calendar->listEvents($client);
        //echo json_encode($events);
    }

    public function checkPost($data){
        if(isset($_POST[''.$data.'']) && !empty($_POST[''.$data.''])){
            //echo $data;
            return $_POST[''.$data.''];
        }else{
            return 'empty';
        }
    }
}


if(isset($_POST['send'])){

    $data=new Data();

    //Initializing object data
    $obj=new stdClass();

    //Gathering object data
    $obj->servicio=$data->checkPost('servicio');
    $obj->nombre=$data->checkPost('nombre');
    $obj->apellido=$data->checkPost('apellido');
    $obj->direccion=$data->checkPost('direccion');
    $obj->ciudad=$data->checkPost('ciudad');
    $obj->telefono=$data->checkPost('telefono');
    $obj->email=$data->checkPost('email');
    $obj->comentario=$data->checkPost('comentario');
    $obj->fecha=$data->checkPost('fecha');
    $obj->hora=$data->checkPost('hora');
    $obj->formapago=$data->checkPost('formapago');

    //Check if appointment exists at date and hour gathered
    $id=0;
    $id=$data->getData($obj);

    if(!empty($id)){
        //Save Data in Db reservas
        //echo json_encode($obj);
        $obj->id=$data->saveData($obj);

        //Save data in db historiareservas
        $data->saveHistoryData($obj);

        //Appointment in Calendar
        $time = strtotime($obj->fecha.' '.$obj->hora);
        $newformat = date('Y-m-d'.'\T'.'H:i:s',$time);
        $start=$newformat.'-05:00';
        $end = date('Y-m-d\TH:i:s',strtotime('+1 hour',strtotime($newformat))).'-05:00';
        $obj->start=$start;
        $obj->end=$end;
        $obj->id=1;
        $data->syncCalendar($obj);

        //Send Email
        $data->sendEmail($obj);
    }else{
        echo 'error: 401';
    }
}


?>