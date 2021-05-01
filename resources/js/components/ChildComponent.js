import React, { Component } from "react";
import { Row, Col, Card } from "react-bootstrap";
import { CardActions, Grid, Typography } from '@material-ui/core';
import * as ReactBootStrap from 'react-bootstrap';
import ProgressBar from 'react-bootstrap/ProgressBar'
import Badge from 'react-bootstrap/Badge'
import { BrowserRouter as Router, Switch, Route, Link } from 'react-router-dom';

class ChildComponent extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <Grid container spacing={3}>
                {this.props.data.map((needie, index) => (
                    <Grid item xs={12} md={4} sm={6} key={index}>
                        <Card style={{ width: '18rem' }} spacing={2}>
                            {/* <Card.Img variant="top">
                                <Badge variant="light">9</Badge>
                            </Card.Img> */
                            }
                            <img
                                src="https://mdbootstrap.com/img/new/standard/city/062.jpg"
                                className="card-img-top"
                                alt="..."
                            />
                            <Badge variant="light">{needie.type} </Badge>
                            <Card.Body>
                                <Card.Title>
                                    <Link to={`/needie/${needie.id}`}> {needie.name}</Link>

                                </Card.Title>
                                <Card.Subtitle className="mb-2 text-muted">  {needie.address}</Card.Subtitle>
                                <Card.Text>
                                    {needie.address}
                                </Card.Text>
                                <CardActions>
                                    <Typography>هدفنا: {needie.need} جنية مصري</Typography>
                                </CardActions>
                                <div>
                                    <ProgressBar variant="success" now={40} label={`${40}%`} />
                                </div>
                                <div style={{ margin: 10, textAlign: "right" }}>
                                    <Link to="/donate" className="btn btn-success">تبرع</Link>
                                </div>
                            </Card.Body>
                        </Card>
                    </Grid>
                )
                )
                }
            </Grid>
        )
    }
}
export default ChildComponent
