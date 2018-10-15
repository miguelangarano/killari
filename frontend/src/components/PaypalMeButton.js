import React from 'react';
import image from '../images/paypal-button.png';

class PaypalMeButton extends React.Component {

  constructor(){
    super();
    this.openPaypalMe=this.openPaypalMe.bind(this);
  }

  openPaypalMe(){
    //console.log('holaaa paypalme');
    this.props.onClicki();
    window.open('https://www.paypal.me/KILLARIEC/'+this.props.precio,'_blank');
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