import React, { Component } from "react";
import { Row, Col, Card } from "react-bootstrap";
import {
    CardActions, Grid, Typography, FormControl,
    InputLabel, OutlinedInput, InputAdornment,
} from '@material-ui/core';
import * as ReactBootStrap from 'react-bootstrap';
import ProgressBar from 'react-bootstrap/ProgressBar'
import Badge from 'react-bootstrap/Badge'
import { BrowserRouter as Router, Switch, Route, Link } from 'react-router-dom';
import Box from '@material-ui/core/Box';
import Button from 'react-bootstrap/Button';
import "./style.css";
import Pagination from "react-js-pagination";

class ChildComponent extends Component {
    constructor(props) {
        super(props);
        console.log(props);
    }
    render() {
        return (
            <React.Fragment>

                <Row>
                    {this.props.data.map((needie, index) => (
                        <Col key={index}>
                            <Card style={{ width: '18rem' }} spacing={2}>
                                <img
                                    src="https://mdbootstrap.com/img/new/standard/city/062.jpg"
                                    className="card-img-top"
                                    alt="..."
                                />
                                <Box
                                    bgcolor="primary.main"
                                    color="primary.contrastText"
                                    p={1}
                                    pr={5}
                                    pl={5}
                                    borderRadius={8}
                                    // top={166}
                                    // left={74}
                                    m="auto"
                                >
                                    {needie.type}
                                </Box>
                                <Card.Body>
                                    {/* <Badge variant="light">{needie.type} </Badge> */}

                                    <Card.Title >
                                        <Link to={`/needie/${needie.id}`}>
                                            <Typography variant="h6" className="link">
                                                {needie.name}
                                            </Typography>
                                        </Link>
                                    </Card.Title>
                                    <Card.Text>
                                        {needie.address}
                                    </Card.Text>
                                    <CardActions>
                                        <Typography>هدفنا: {needie.need} ج.م</Typography>
                                    </CardActions>
                                    <div>
                                        <ProgressBar variant="primary"
                                            now={(needie.collected / needie.need) * 100}
                                            label={`${(needie.collected / needie.need) * 100}%`}
                                            className="progressbartext"
                                        />
                                    </div>
                                    <Card.Subtitle className="mt-2 text-muted small text-right">{needie.collected}ج.م</Card.Subtitle>

                                    <Grid container>
                                        <Grid item xs={4}>
                                            <Link to={`/donate/${needie.id}`} className="btn btn-primary">
                                                <Button variant="outlined" color="primary">
                                                    تبرع
                                            </Button>
                                            </Link>
                                        </Grid>
                                        <Grid item xs={8}>
                                            <FormControl fullWidth variant="outlined">
                                                <InputLabel htmlFor="outlined-adornment-amount">المبلغ</InputLabel>
                                                <OutlinedInput
                                                    id="outlined-adornment-amount"
                                                    type="number"
                                                    startAdornment={<InputAdornment position="start">£</InputAdornment>}
                                                />
                                            </FormControl>
                                        </Grid>
                                    </Grid>
                                </Card.Body>
                            </Card>
                        </Col>
                    )
                    )
                    }
                </Row>

                <Pagination
                    activePage={1}
                    itemsCountPerPage={10}
                    totalItemsCount={450}
                    pageRangeDisplayed={5}
                />
            </React.Fragment>


        )
    }
}
export default ChildComponent
