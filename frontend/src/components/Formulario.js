import React, { Component } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import {Col, Row, Button, Form, FormGroup, Label, Input, Container, Fade, Badge} from 'reactstrap';
import PaypalButton from './PaypalButton';
import Modal from './Modal';
import axios from 'axios';
import proxy from '../config/proxy/proxy';
//import logo from './logo.svg';
//import './App.css';

const CLIENT = {
  sandbox: '',
  production: '',
};

const ENV = process.env.NODE_ENV === 'production'
  ? 'production'
  : 'sandbox';

class Formulario extends Component {


  constructor(){
    super();
    this.state = { 
      fadeIn: false, fadePrecio:false, fadeOcupado:false,
      servicio:'',
      nombre:'',
      apellido:'',
      direccion:'',
      ciudad:'',
      telefono:'',
      email:'',
      comentario:'',
      fecha:'',
      hora:'',
      formapago:'',
      verificacion:'',
      modal:false,
      message:'',
      type:'',
      color:''
    };
    this.toggle = this.toggle.bind(this);
    this.onClickReservar=this.onClickReservar.bind(this);
  }

  toggle() {
    this.setState({
      modal: !this.state.modal
    });
    if(this.state.color==='success'){
      window.location = 'www.killari.com.ec';
    }
  }

  handle=(event)=>{
    switch(event.target.name){
      case 'servicio':{
        if(event.target.value==='Por favor, seleccione el servicio'){
          this.setState({
            fadePrecio: false,
            servicio:''
          });
        }else{
          this.setState({
            fadePrecio: true,
            servicio:event.target.value
          });
        }
        break;
      }
      case 'nombre':{
        this.setState({
          nombre:event.target.value
        });
        break;
      }
      case 'apellido':{
        this.setState({
          apellido:event.target.value
        });
        break;
      }
      case 'direccion':{
        this.setState({
          direccion:event.target.value
        });
        break;
      }
      case 'ciudad':{
        this.setState({
          ciudad:event.target.value
        });
        break;
      }
      case 'telefono':{
        this.setState({
          telefono:event.target.value
        });
        break;
      }
      case 'email':{
        this.setState({
          email:event.target.value
        });
        break;
      }
      case 'comentario':{
        this.setState({
          comentario:event.target.value
        });
        break;
      }
      case 'fecha':{
        this.setState({
          fecha:event.target.value
        });
        break;
      }
      case 'hora':{
        this.setState({
          hora:event.target.value
        });
        break;
      }
      case 'verificacion':{
        if(event.target.value==='3'){
          this.setState({
            verificacion:event.target.value
          });
        }else{
          console.log('error');
        }
        break;
      }
      default:{
        break;
      }
    }
  }

  onClickReservar(){
    let reserva={
      servicio:this.state.servicio,
      nombre:this.state.nombre,
      apellido:this.state.apellido,
      direccion:this.state.direccion,
      ciudad:this.state.ciudad,
      telefono:this.state.telefono,
      email:this.state.email,
      comentario:this.state.comentario,
      fecha:this.state.fecha,
      hora:this.state.hora,
      formapago:this.state.formapago,
      verificacion:this.state.verificacion
    };
    //console.log(reserva);
    if(reserva.nombre!=='' && reserva.apellido!=='' && reserva.telefono!=='' && reserva.email!=='' && reserva.servicio!=='' && reserva.fecha!=='' && reserva.hora!==''  && reserva.formapago!=='' && reserva.verificacion!==''){
      //console.log('ok');
      this.agregarReserva(reserva);
    }else{
      //console.log('error');
      this.setState({
        modal:true,
        type:'Error',
        message:'Debe llenar todos los campos marcados con un asterisco rojo.',
        color:'danger'
      });
    }
  }

  agregarReserva=(reserva)=>{
    let data=new FormData();
    data.append('send','1');
    data.append('servicio',reserva.servicio);
    data.append('nombre',reserva.nombre);
    data.append('apellido',reserva.apellido);
    data.append('direccion',reserva.direccion);
    data.append('ciudad',reserva.ciudad);
    data.append('telefono',reserva.telefono);
    data.append('email',reserva.email);
    data.append('comentario',reserva.comentario);
    data.append('fecha',reserva.fecha);
    data.append('hora',reserva.hora);
    data.append('formapago',reserva.formapago);
    axios.post(proxy+'/request.php', data)
      .then(function (response) {
        // handle success
        if(response.data==='ok'){
          this.setState({
            modal:true,
            type:'Exito',
            message:'Su reservación ha sido recibida correctamente. Le llegará un correo confirmando su pedido.',
            color:'success'
          });
        }else{
          this.setState({
            modal:true,
            type:'Error',
            message:'Hubo un error al realizar su reservación. Por favor, inténtelo de nuevo. Error: '+response,
            color:'danger'
          });
        }
      }).catch(function (error) {
        // handle error
        this.setState({
          modal:true,
          type:'Error',
          message:'Hubo un error al realizar su reservación. Por favor, inténtelo de nuevo. Error: '+error,
          color:'danger'
        });
      });
  }


  onClickPago=(event)=>{
    switch(event.target.id){
      case 'efectivo':{
        this.setState({
          formapago:'efectivo',
          fadeIn: false
        });
        break;
      }
      case 'paypal':{
        this.setState({
          formapago:'paypal',
          fadeIn: !this.state.fadeIn
        });
        break;
      }
      default:{
        break;
      }
    }

  }


  render() {

    const onSuccess = (payment) =>
      console.log('Successful payment!', payment);

    const onError = (error) =>
      console.log('Erroneous payment OR failed to load script!', error);

    const onCancel = (data) =>
      console.log('Cancelled payment!', data);

    const num1=1;
    const num2=2;
    const precio=15.99;

      
    return (
      <Container style={styleForm}>
        <Modal modal={this.state.modal} toggle={this.toggle} message={this.state.message} type={this.state.type} color={this.state.color}></Modal>
        
        <h1 style={styling}>RESERVE UNA CITA</h1>
        <br></br>
        <br></br>
        <hr size="10"></hr>
        <br></br>
        
        <Label style={stylingLabel}>INFORMACIÓN PERSONAL</Label>
        <Row >
          <Col md={6}>
            <FormGroup>
              <Label style={stylingSubLabel}>Nombre <font color="red">*</font></Label>
              <Input type="text" name="nombre" onChange={this.handle.bind(this)}/>
            </FormGroup>
          </Col>
          <Col md={6}>
              <FormGroup>
                <Label style={stylingSubLabel}>Apellido <font color="red">*</font></Label>
                <Input type="text" name="apellido" onChange={this.handle.bind(this)}>
                </Input>
              </FormGroup>
          </Col>
        </Row>
        <FormGroup>
          <Label style={stylingSubLabel}>Dirección</Label>
          <Input type="text" name="direccion" onChange={this.handle.bind(this)}/>
        </FormGroup>
        <FormGroup>
          <Label style={stylingSubLabel}>Ciudad</Label>
          <Input type="text" name="ciudad" onChange={this.handle.bind(this)}/>
        </FormGroup>
        <Row>
          <Col md={6}>
            <FormGroup>
              <Label style={stylingSubLabel}>Teléfono <font color="red">*</font></Label>
              <Input type="text" name="telefono" onChange={this.handle.bind(this)}/>
            </FormGroup>
          </Col>
          <Col md={6}>
              <FormGroup>
                <Label style={stylingSubLabel}>Email <font color="red">*</font></Label>
                <Input type="text" name="email" onChange={this.handle.bind(this)}>
                </Input>
              </FormGroup>
          </Col>
        </Row>
        <FormGroup>
          <Label style={stylingSubLabel}>Comentario</Label>
          <Input type="text" name="comentario" onChange={this.handle.bind(this)}/>
        </FormGroup>
        <br></br>
        <hr size="10"></hr>
        <br></br>
        <Form>
          <Label style={stylingLabel}>SERVICIO Y FECHA</Label>
          <FormGroup>
            <Label style={stylingSubLabel}>Servicio <font color="red">*</font></Label>
            <Input type="select" name="servicio" placeholder="Por favor, seleccione el servicio" onChange={this.handle.bind(this)}>
              <option>Por favor, seleccione el servicio</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
            </Input>
          </FormGroup>    
        <Row>
          <Col md={6}>
            <FormGroup>
              <Label style={stylingSubLabel}>Fecha <font color="red">*</font></Label>
              <Input type="date" name="fecha" placeholder="date-placeholder" onChange={this.handle.bind(this)} />
            </FormGroup>
          </Col>
          <Col md={6}>
              <FormGroup>
                <Label style={stylingSubLabel}>Hora <font color="red">*</font></Label>
                <Input type="select" name="hora" onChange={this.handle.bind(this)}>
                  <option>11:00</option>
                  <option>12:00</option>
                  <option>13:00</option>
                  <option>14:00</option>
                  <option>16:00</option>
                  <option>17:00</option>
                  <option>18:00</option>
                  <option>19:00</option>
                  <option>20:00</option>
                  <option>21:00</option>
                  <option>22:00</option>
                  <option>23:00</option>
                </Input>
              </FormGroup>
          </Col>
        </Row>
        <br></br>
        <hr size="10"></hr>
        <br></br>
        <Label style={stylingLabel}>FORMA DE PAGO <font color="red">*</font></Label>
        <br></br>
          <Row>
            <Col md={6}>
              <FormGroup name='radios' tag="fieldset" onChange={this.onClickPago.bind(this)}>
                <FormGroup check>
                  <Label check>
                    <Input type="radio" name="radio1" id="efectivo" />{' '}
                    Efectivo
                  </Label>
                </FormGroup>
                <FormGroup check>
                  <Label check>
                    <Input type="radio" name="radio1" id="paypal" />{' '}
                    Paypal
                  </Label>
                </FormGroup>
              </FormGroup>
              <Fade in={this.state.fadeIn} tag="h5" className="mt-3" >
                <PaypalButton
                  client={CLIENT}
                  env={ENV}
                  commit={true}
                  currency={'USD'}
                  total={100}
                  onSuccess={onSuccess}
                  onError={onError}
                  onCancel={onCancel} />
              </Fade>
            </Col>
            <Col md={6}>
            <Fade in={this.state.fadePrecio}  className="mt-3">
              <h1>Precio: <Badge color="success">${precio}</Badge></h1>
            </Fade>
            
            </Col>
          </Row>
        <hr size="10"></hr>
        <br></br>
        <Label style={stylingLabel}>VERIFICACIÓN <font color="red">*</font></Label>
        <FormGroup>
            <Label style={stylingSubLabel}>Por favor, ingrese el resultado de la suma.</Label>
            <Input type="text" name="verificacion" onChange={this.handle.bind(this)}/>
            <h6>¿Cuál es la suma entre {num1} + {num2}</h6>
        </FormGroup>
      </Form>
      <br></br>
      <br></br>
      <Button style={buttonStyle} onClick={()=>this.onClickReservar()} >Reservar</Button>
      <br></br><br></br>
      </Container>
    );
  }
}

var styling={
  "textAlign":"center",
  "color":"#00a8a9",
  "fontFamily":"Arial, Helvetica, sans-serif",
  "fontStyle":"oblique",
  "fontSize":"20pt"
}

var stylingLabel={
  "color":"#00a8a9",
  "fontFamily":"Arial, Helvetica, sans-serif",
  "fontWeight":"bold"
}

var stylingSubLabel={
  "fontFamily":"Arial, Helvetica, sans-serif",
  "fontWeight":"bold"
}

var buttonStyle={
  "backgroundColor":"#00a8a9",
  "border": "none",
  "color": "white",
  "padding": "15px 32px",
  "textAlign": "center",
  "textDecoration": "none",
  "display": "inline-block",
  "fontSize": "16px",
  
}

var styleForm={
  "paddingTop":"160px",
  "zIndex": "-1"
}


export default Formulario;
