import { Card } from '@material-ui/core';
import axios from 'axios';
import React from 'react';
import { useState } from 'react';
import { CardBody, CardText, CardTitle } from 'reactstrap';
import ProgressBar from 'react-bootstrap/ProgressBar';
import { Media } from 'react-bootstrap';
import Container from 'react-bootstrap/Container'
import { Col, Row, Form } from "react-bootstrap";

class Needie extends React.Component {
    constructor(props) {
        super(props);
        console.log(this.props)
        this.state = {
            id: this.props.match.params.id,
            needie: []
        }
    }
    loadNeedie() {
        let id = this.state.id;
        let url = '/api/ahed/needies/' + id;
        axios.get(url)
            .then((response) => {
                this.setState({
                    needie: response.data.data,
                })
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
                <Container>
                    <Card>
                        <h5>{needieName}</h5>
                        <CardBody>
                            <Row>
                                <Col md={8}>
                                    <p>
                                        {needieDetails}
                                    </p>
                                </Col>
                                <Col md={4}>
                                    <img
                                        src="/img/28483.jpg"
                                        alt="Generic placeholder"
                                    />
                                </Col>
                            </Row>
                            <Row>
                                <Col  md={8}>
                                    <ProgressBar variant="success" now={40} label={`${40}%`} />
                                </Col>
                            </Row>
                        </CardBody>
                    </Card>

                </Container>

            </div >

        );

    }

}
export default Needie;