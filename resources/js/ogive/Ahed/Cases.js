import React, { Component } from "react";
import { Row, Col } from "react-bootstrap";
import {
    Card, CardActions, CardContent, Grid, Typography, FormControl,
    InputLabel, OutlinedInput, InputAdornment,
    Container
} from '@material-ui/core';
import { sizing } from '@material-ui/system';

import * as ReactBootStrap from 'react-bootstrap';
import ProgressBar from 'react-bootstrap/ProgressBar'
import Badge from 'react-bootstrap/Badge'
import { BrowserRouter as Router, Switch, Route, Link } from 'react-router-dom';
import Box from '@material-ui/core/Box';
import Button from 'react-bootstrap/Button';
import Pagination from "react-js-pagination";
import { createTheme, ThemeProvider } from '@material-ui/core/styles';
import {
    Hero, CallToAction, ScrollDownIndicator
} from 'react-landing-page';
const theme = createTheme({
    typography: {
        subtitle1: {
            fontSize: 12,
        },
        body1: {
            fontWeight: 500,
        },
        body2: {
            height: 100,
        },
        button: {
            fontStyle: 'italic',
        },
    },
});
class Cases extends Component {
    constructor() {
        super();
        this.state = {
            needies: [],
            current_page: 1,
            total: 1,
            per_page: 1,
            activePage: 15
        }
        this.handlePageChange = this.handlePageChange.bind(this);
    }
    handlePageChange(pageNumber) {
        console.log(`active page is ${pageNumber}`);
        this.setState({ current_page: pageNumber }, () => { this.loadNeedies(); });
    }
    loadNeedies() {
        let url = `http://127.0.0.1:8000/api/ahed/needies?page=${this.state.current_page}`;
        axios.get(url)
            .then((response) => {
                console.log(response.data.data.data)
                this.setState({
                    needies: response.data.data.data,
                    current_page: response.data.data.current_page,
                    total: response.data.data.total,
                    per_page: response.data.data.per_page,
                }, () => { console.log(this.state) })
            })
            .catch(error => {
                console.log(error)
            })
    }
    componentDidMount() {
        this.loadNeedies();
        console.log("call")
    }

    render() {
        return (
            <React.Fragment>
                <Container>
                    <Row id="cases">
                        {this.state.needies && this.state.needies.map((needie, index) => (
                            <Col key={index}>
                                <Card style={{ width: '18rem' }} spacing={2} elevation={1} height={40} >
                                    <img
                                        src="https://mdbootstrap.com/img/new/standard/city/062.jpg"
                                        className="card-img-top"
                                        alt="..."
                                    />
                                    <Box
                                        bgcolor="primary.main"
                                        color="primary.contrastText"
                                        p={1}
                                        px={5}
                                        borderRadius={8}
                                        mx={5}
                                        textAlign="center"
                                    >
                                        {needie.type}
                                    </Box>
                                    <CardContent>
                                        {/* <Badge variant="dot">{needie.type} </Badge> */}
                                        {/* <Card.Title >
                                            <Link to={`/ahed/needie/${needie.id}`}>
                                                <Typography variant="h6" className="link">
                                                    {needie.name}
                                                </Typography>
                                            </Link>
                                        </Card.Title> */}
                                        {/* <Card.Text>
                                            {needie.address}
                                        </Card.Text> */}
                                        <Typography color="textSecondary" component="p" style={{ height: "100%" }}>
                                            <Link to={`/ahed/needie/${needie.id}`}>
                                                <Typography variant="h6" className="link">
                                                    {needie.name}
                                                </Typography>
                                            </Link>
                                        </Typography>
                                        <CardActions>
                                            <Typography>هدفنا: {needie.need} ج.م</Typography>
                                        </CardActions>
                                        <Box pb={2}>
                                            <ProgressBar variant="primary"
                                                now={(needie.collected / needie.need) * 100}
                                                label={`${(needie.collected / needie.need) * 100}%`}
                                                className="progressbartext"
                                            />
                                        </Box>
                                        {/* <Card.Subtitle className="mt-2 text-muted small text-right">{needie.collected}ج.م</Card.Subtitle> */}
                                        <Grid container>
                                            <Grid item xs={4}>
                                                <Link to={`/donate/${needie.id}`} className="btn btn-primary">
                                                    <Button variant="outlined" color="primary">
                                                        تبرع
                                                    </Button>
                                                </Link>
                                            </Grid>
                                        </Grid>
                                    </CardContent>
                                </Card>
                            </Col>
                        )
                        )
                        }
                    </Row>
                    <Pagination
                        activePage={this.state.current_page}
                        itemsCountPerPage={this.state.per_page}
                        totalItemsCount={this.state.total}
                        onChange={this.handlePageChange.bind(this)}
                        itemClass="page-item"
                        linkClass="page-link"
                    />
                    <hr className="featurette-divider" />

                    <div className="row featurette">
                        <div className="col-md-7">
                            <h2 className="featurette-heading">متوفر الآن <span className="text-muted">عهد بين يديك</span></h2>
                            <p className="lead">ابدأ ودير جمع التبرعات ، وتفاعل مع المؤيدين ، واكتشف الأسباب المهمة - كل ذلك أثناء التنقل</p>
                            <div className="row">
                                <div className="col-md-6">
                                    <img src="/img/appStore.png" />
                                </div>
                                <div className="col-md-6">
                                    <img src="/img/appStore.png" />
                                </div>
                            </div>
                        </div>
                        <div className="col-md-5">
                            {/* <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa" dy=".3em">500x500</text></svg> */}
                            <img src="/img/use-our-app.jpg" alt="app" height="500" width="500" />
                        </div>
                    </div>
                </Container>

            </React.Fragment>


        )
    }
}
export default Cases