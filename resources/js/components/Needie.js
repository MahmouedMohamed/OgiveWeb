import { Card } from '@material-ui/core';
import axios from 'axios';
import React from 'react';
import { useState } from 'react';
import { CardBody, CardText } from 'reactstrap';
import ProgressBar from 'react-bootstrap/ProgressBar';
import { Media } from 'react-bootstrap';
import Container from 'react-bootstrap/Container'
import { Col, Row, Form } from "react-bootstrap";

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
        const mystyle = {
            color: "white",
            backgroundColor: "DodgerBlue",
            padding: "10px",
            fontFamily: "Arial"
        };
        return (
            <div>
                <Container>
                    <div className="card mb-3">
                        <div className="row no-gutters">
                            <div className="col-md-6">
                                <img
                                    src="/img/poorboy.jpg"
                                    alt="Generic placeholder"
                                />
                            </div>
                            <div className="col-md-6">
                                <div className="card-body">
                                    <h5 className="card-title">Card title</h5>
                                    <p className="card-text">
                                        Card Text
            </p>
                                    <p className="card-text">
                                        <small className="text-muted">Card Text 2</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Card>
                        <CardText>SHOW</CardText>
                        <CardBody>
                            <Media className="justify-content-end">
                                <Media.Body>
                                    <Row>
                                        <Col>
                                            <img
                                                width={100}
                                                height={500}
                                                className="ml-3"
                                                src="/img/poorboy.jpg"
                                                alt="Generic placeholder"
                                            />
                                        </Col>
                                        <Col>
                                            <Row>
                                                <Col>
                                                    <h5>{needieName}</h5>
                                                    <p>
                                                        {needieDetails}
                                                    </p>
                                                    <div>
                                                        <ProgressBar variant="success" now={40} label={`${40}%`} />
                                                    </div>

                                                    <Form inline>
                                                        <Form.Group>
                                                            <Form.Label htmlFor="inputPassword6">ادخل القيمة النقدية هنا</Form.Label>
                                                            <Form.Control
                                                                type="password"
                                                                className={mystyle}
                                                                id="inputPassword6"
                                                                aria-describedby="passwordHelpInline"
                                                            />

                                                        </Form.Group>
                                                    </Form>
                                                </Col>

                                            </Row>
                                        </Col>
                                    </Row>
                                </Media.Body>

                            </Media>
                        </CardBody>
                    </Card>

                </Container>

            </div>

        );

    }

}
export default Needie;