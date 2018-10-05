<?php

header('Access-Control-Allow-Origin: *');
require_once('./libs/phpmailer/email.php');
require_once('Db.php');


class Data{

    public $proxy='http://www.killari.com.ec/reservaciones/backend/';
    //public $proxy='http://localhost/killari/backend/';

    public function saveData($obj){
        $db=Db::conectar();
        $insert=$db->prepare('INSERT INTO reservas VALUES(NULL, :servicio, :fecha, :hora, :nombre, :apellido, :direccion, :ciudad, :telefono, :email, :comentario, :formapago  )');
        $insert->bindValue('servicio',$obj->servicio);
        $insert->bindValue('nombre',$obj->nombre);
        $insert->bindValue('apellido',$obj->apellido);
        $insert->bindValue('direccion',$obj->direccion);
        $insert->bindValue('ciudad',$obj->ciudad);
        $insert->bindValue('telefono',$obj->telefono);
        $insert->bindValue('email',$obj->email);
        $insert->bindValue('comentario',$obj->comentario);
        $insert->bindValue('fecha',$obj->fechadb);
        $insert->bindValue('hora',$obj->hora);
        $insert->bindValue('formapago',$obj->formapago);
        $insert->execute();
        $LAST_ID = $db->lastInsertId();
        $insert=$db->prepare('UPDATE veces SET times=:times');
        $insert->bindValue('times',$LAST_ID);
        $insert->execute();
		return $LAST_ID;
    }

    public function saveHistoryData($obj){
        $db=Db::conectar();
        $insert=$db->prepare('INSERT INTO historiareservas VALUES(NULL, :id_reserva, :servicio, :fecha, :hora, :nombre, :apellido, :direccion, :ciudad, :telefono, :email, :comentario, :formapago  )');
        $insert->bindValue('id_reserva', $obj->id);
        $insert->bindValue('servicio',$obj->servicio);
        $insert->bindValue('nombre',$obj->nombre);
        $insert->bindValue('apellido',$obj->apellido);
        $insert->bindValue('direccion',$obj->direccion);
        $insert->bindValue('ciudad',$obj->ciudad);
        $insert->bindValue('telefono',$obj->telefono);
        $insert->bindValue('email',$obj->email);
        $insert->bindValue('comentario',$obj->comentario);
        $insert->bindValue('fecha',$obj->fechadb);
        $insert->bindValue('hora',$obj->hora);
        $insert->bindValue('formapago',$obj->formapago);
        $insert->execute();
        $LAST_ID = $db->lastInsertId();
        if(!empty($LAST_ID)){
            return 'ok';
        }
    }

    public function getLastId(){
        $db=Db::conectar();
        $select=$db->prepare('SELECT * FROM veces');
        $select->execute();
        $cita=$select->fetch();
        $id=$cita['times'];
        return $id;
    }

    public function getData($obj){
        $db=Db::conectar();
        $select=$db->prepare('SELECT * FROM reservas WHERE fecha=:fecha AND hora=:hora');
        $select->bindValue('fecha',$obj->fechadb);
        $select->bindValue('hora',$obj->hora);
        $select->execute();
        $cita=$select->fetch();
        $id=$cita['id_reserva'];
        return $id;
    }

    public function sendEmail($obj){
        $email=new Email();
        return $email->sendEmail($this->proxy, $obj, 1);
        //$email->sendEmail('centroderelajacion@killari.com.ec', $this->proxy, $obj->nombre, $obj->fecha, $obj->hora, $obj->servicio, 0);
    }

    public function sendErrorEmail($obj){
        $email=new Email();
        $email->sendErrorEmail($this->proxy, $obj);
        //$email->sendEmail('centroderelajacion@killari.com.ec', $this->proxy, $obj->nombre, $obj->fecha, $obj->hora, $obj->servicio, 0);
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
    //echo $obj->fecha;
    $fechaDb=strtotime($obj->fecha);
    $obj->fechadb=date('Y-m-d',$fechaDb);
    //echo $obj->fechadb;

    //Check if appointment exists at date and hour gathered
    $id=0;
    $id=$data->getData($obj);

    if(empty($id)){
        //Save Data in Db reservas
        //echo json_encode($obj);
        $ok='';
        //Send Email
        $obj->id=$data->getLastId()+1;
        $ok=$data->sendEmail($obj);
        //echo json_encode($ok);
        
        if($ok=='ok'){
            //Save data in db historiareservas
            $obj->id=$data->saveData($obj);
            $ok=$data->saveHistoryData($obj);
            if($ok=='ok'){
                echo json_encode($ok);
            }else{
                $ok='error 403';
                $data->sendErrorEmail($obj);
                echo json_encode($ok);
            }
        }else{
            $ok='error 402';
            echo json_encode($ok);
        }
        
        
    }else{
        echo json_encode('error: 401');
    }
}


?>