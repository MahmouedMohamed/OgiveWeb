import { Typography } from '@material-ui/core';
import React, { memo } from 'react'
import { Row, Col, Card, Container } from "react-bootstrap";
import 'font-awesome/css/font-awesome.min.css';
import './style.css';
function Footer() {
    return (
        <div>
            <footer color="blue" className="font-small pt-4 mt-4 footer">
                <Container fluid className="text-center text-md-left">
                    <Row>
                        <Col md="2">
                            <h5 className="title">اشترك في النشرة البريدية</h5>


                        </Col>
                        <Col md="6">
                            <h5 className="title">روابط سريعة</h5>
                            <ul>
                                <li className="list-unstyled">
                                    <a href="#!">كيفية التبرع</a>
                                </li>
                                <li className="list-unstyled">
                                    <a href="#!">المكتب الرئيسى</a>
                                </li>
                                <li className="list-unstyled">
                                    <a href="#!">اتصل بنا</a>
                                </li>
                            </ul>
                        </Col>
                        <Col>
                            <Typography className="pb-5">شبكات التواصل الاجتماعي</Typography>

                            <Typography>
                                <p>اتصل بنا على 16602 <i className="ri-phone-fill"></i></p>
                            </Typography>
                        </Col>
                    </Row>
                </Container>
                <div className="footer-copyright text-center py-3">
                    <Container fluid>
                        &copy; {new Date().getFullYear()} Copyright: <a href="/"> Ogive.org </a>
                    </Container>
                </div>
            </footer>
        </div>
    )
}

export default Footer;

