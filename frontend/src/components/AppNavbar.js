import React, { Component } from 'react';
import {
  Collapse,
  Navbar,
  NavbarToggler,
  NavbarBrand,
  Nav,
  NavItem,
  Container,
  Button
} from 'reactstrap';
import logo from '../images/logo-killari.png';


class AppNavbar extends Component {


    state = {
        isOpen: false
      };



    toggle = () => {
        this.setState({
          isOpen: !this.state.isOpen
        });
      };


    onClickCancelar(){
        console.log("cancelar");
    }



  render() {

    return (
      <div>
        <Navbar color="light" light  expand="sm" className="mb-5" style={styleBar}>
          <Container>
            <NavbarBrand href="/"><img src={logo} alt=""/></NavbarBrand>
            <NavbarToggler style={styling} onClick={this.toggle} />
            <Collapse isOpen={this.state.isOpen} navbar>
              <Nav className="ml-auto" navbar>
                <NavItem>
                  <Button size="sm" style={styling} onClick={()=>this.onClickCancelar()}>Cancelar</Button>
                </NavItem>
              </Nav>
            </Collapse>
          </Container>
        </Navbar>
      </div>
    );
  }
}

var styleBar={
  "position":"fixed",
  "zIndex":"2",
  "width": "100%"
}

var styling={
    "backgroundColor":"#00a8a9"
}

export default AppNavbar;