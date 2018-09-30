import React, { Component } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import AppNavbar from './components/AppNavbar';
import Formulario from './components/Formulario';

//import logo from './logo.svg';
//import './App.css';

class App extends Component {

  render() {
    return (
      <div className="App">
      <AppNavbar></AppNavbar>
      <Formulario></Formulario>
      </div>
    );
  }
}

export default App;
