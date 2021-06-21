import React from 'react';
import * as ReactBootStrap from 'react-bootstrap';
import { BrowserRouter as Router, Switch, Route, Link } from 'react-router-dom';
import Button from 'react-bootstrap/Button';
import { Box } from '@material-ui/core';
function Navbar() {
    return (
        <div>
            <ReactBootStrap.Navbar bg="light" expand="lg">
                {/* <ReactBootStrap.Navbar.Brand href="#home"><i className="fab fa-github fa-lg	"></i></ReactBootStrap.Navbar.Brand> */}
                <ReactBootStrap.Navbar.Toggle aria-controls="basic-navbar-nav" />
                <ReactBootStrap.Navbar.Collapse id="basic-navbar-nav">
                    <ReactBootStrap.Nav className="ml-auto">
                        {/* <ReactBootStrap.Nav.Link to='/home'>Section 1</ReactBootStrap.Nav.Link> */}
                        <Link to="/" className="nav-link">الصفحة الرئيسية</Link>
                        <Link to="/" className="nav-link">عن Ogive</Link>
                        <ReactBootStrap.NavDropdown title="مشارعنا" id="navbarScrollingDropdown">
                            <ReactBootStrap.NavDropdown.Item href="/ahed/ahed">عهد</ReactBootStrap.NavDropdown.Item>
                            <ReactBootStrap.NavDropdown.Item href="/pets">Breed Me</ReactBootStrap.NavDropdown.Item>
                        </ReactBootStrap.NavDropdown>
                        <Link to="/" className="nav-link">تواصل معنا</Link>

                    </ReactBootStrap.Nav>
                </ReactBootStrap.Navbar.Collapse>
                <ReactBootStrap.Navbar.Collapse id="basic-navbar-nav">
                    <ReactBootStrap.Nav className="mr-auto">
                        <ReactBootStrap.Nav.Link href="/login">تسجيل</ReactBootStrap.Nav.Link>
                        <ReactBootStrap.Nav.Link href="/register">اشتراك</ReactBootStrap.Nav.Link>
                        <Box pr="2">
                            <img src="img/ogive.png" width="30"
                                height="30"></img>
                        </Box>
                    </ReactBootStrap.Nav>
                </ReactBootStrap.Navbar.Collapse>
            </ReactBootStrap.Navbar>
        </div >
    );
}
export default Navbar;
