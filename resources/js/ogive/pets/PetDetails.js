import axios from 'axios';
import React from 'react';
import { useState } from 'react';
// import { CardBody, CardText } from 'reactstrap';
import ProgressBar from 'react-bootstrap/ProgressBar';
import { Media } from 'react-bootstrap';
// import Container from 'react-bootstrap/Container'
import { Col, Row, Form } from "react-bootstrap";
import { Container, Grid, Card, CardMedia, CardBody, CardContent, CardActions, Typography, Button, Carousel } from '@material-ui/core';

class PetDetails extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.match.params.id,
            pet: []
        }
    }
    loadNeedie() {
        let id = this.state.id;
        let url = 'http://127.0.0.1:8000/api/pets/' + id;
        axios.get(url)
            .then((response) => {
                this.setState({
                    pet: response.data.data,
                })
                console.log(response.data.data)

            })
            .catch(error => {
                console.log(error)
            })
    }
    componentDidMount() {
        this.loadNeedie()
        console.log("Iam Details")

    }
    render() {
        let petName = this.state.pet.name
        let petType = this.state.pet.type

        const mystyle = {
            color: "white",
            backgroundColor: "DodgerBlue",
            padding: "10px",
        };
        return (
            <div>
                <nav className="navbar navbar-expand-lg navbar-light bg-light">
                    {/* <a class="navbar-brand" href="#">Breed Me</a> */}
                    <a className="navbar-brand" href="#">
                        <img src="img/adopt_icn.jpg" className="d-inline-block align-top" alt="" />
                    </a>
                    <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    <div className="collapse navbar-collapse" id="navbarNav">
                        <ul className="navbar-nav">
                            <li className="nav-item active">
                                <a className="nav-link" href="/pets">الصفحة الرئيسية <span className="sr-only">(current)</span></a>
                            </li>
                            <li className="nav-item">
                                <a className="nav-link" href="#">عن Breed Me</a>
                            </li>
                            <li className="nav-item">
                                <a className="nav-link" href="#">أخبار و أحداث</a>
                            </li>
                            <li className="nav-item">
                                <a className="nav-link" href="#">تواصل معنا</a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <Container>
                    <Card>

                        <p>{petName}</p>
                        <p>{petType}</p>
                        {/* <p>{this.state.pet}</p> */}


                    </Card>
                </Container>

            </div>

        );

    }

}
export default PetDetails;

// import React from 'react';

// function PetDetails() {
//     return (
//         <div>
//             <p>PetDetails</p>
//         </div>
//     );
// }

// export default PetDetails;
