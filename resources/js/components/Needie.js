import { Card } from '@material-ui/core';
import axios from 'axios';
import React from 'react';
import { useState } from 'react';
import { CardBody, CardText } from 'reactstrap';
import ReactDOM from 'react-dom';
import { Media } from 'react-bootstrap';
class Needie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.match.params.id,
            needie: []
        }
    }
    loadNeedie() {
        let id = this.state.id;
        let url = 'http://127.0.0.1:8000/api/ahed/needies/' + id;
        axios.get(url)
            .then((response) => {
                this.setState({
                    needie: response.data.data,
                })
                console.log(response.data.data)

            })
            .catch(error => {
                console.log(error)
            })
    }
    componentDidMount() {
        this.loadNeedie()

    }
    render() {
        let needieName = this.state.needie.name
        let needieType = this.state.needie.type
        let needieDetails = this.state.needie.details
        let needieNeed = this.state.needie.need
        return (
            <div>
                <Card>
                    <CardText>SHOW</CardText>
                    <CardBody>
                        <Media className="justify-content-end">
                            <Media.Body>
                                {/* Show slider here by before Images */}
                                <h5>{needieName}</h5>
                                <p>
                                    {needieDetails}
                                </p>
                            </Media.Body>
                            <img
                                width={64}
                                height={64}
                                className="ml-3"
                                src="/img/poorboy.jpg"
                                alt="Generic placeholder"
                            />
                        </Media>
                    </CardBody>
                </Card>
            </div>

        );

    }

}
export default Needie;