import React from 'react';
import ReactDOM from 'react-dom';
import scriptLoader from 'react-async-script-loader';
import image from '../images/paypal-button.png';

class PaypalMeButton extends React.Component {

  constructor(){
    super();
    this.openPaypalMe=this.openPaypalMe.bind(this);
  }

  openPaypalMe(){
    console.log('holaaa paypalme');
    window.location.href='https://www.paypal.me/KILLARIEC';
  }
  
  render() {
  
      return (
        <div>
          <img src={image} alt="" style={styling} onClick={()=>this.openPaypalMe()}></img>
        </div>

      );
  }
}

var styling={
  "height":"40px",
  "width":"150px"
}

export default PaypalMeButton;