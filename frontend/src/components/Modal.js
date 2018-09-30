import React,{Component} from 'react';
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';

class ModalShow extends Component {

  render() {
    return (
      <div>
        <Modal isOpen={this.props.modal} toggle={this.props.toggle}>
          <ModalHeader>{this.props.type}</ModalHeader>
          <ModalBody>
            {this.props.message}
          </ModalBody>
          <ModalFooter>
            <Button color={this.props.color} onClick={this.props.toggle}>Aceptar</Button>
          </ModalFooter>
        </Modal>
      </div>
    );
  }
}

export default ModalShow;