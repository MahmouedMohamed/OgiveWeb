import React from 'react';
import * as ReactBootStrap from 'react-bootstrap';
import { BrowserRouter as Router, Switch, Route, Link } from 'react-router-dom';
import Button from 'react-bootstrap/Button';

function Navbar() {
    return (
        <div>
            <ReactBootStrap.Navbar bg="light" expand="lg">
                <ReactBootStrap.Navbar.Toggle aria-controls="basic-navbar-nav" />
                <ReactBootStrap.Navbar.Collapse id="basic-navbar-nav">
                    <ReactBootStrap.Nav className="ml-auto">
                        {/* <ReactBootStrap.Nav.Link to='/home'>Section 1</ReactBootStrap.Nav.Link> */}
                        <Link to="/ahed/ahed" className="nav-link">الصفحة الرئيسية</Link>
                        <Link to="/ahed/about" className="nav-link">عن عهد</Link>
                        <ReactBootStrap.Nav.Link href="">التبرعات</ReactBootStrap.Nav.Link>
                        <ReactBootStrap.Nav.Link href="#link">تطوع</ReactBootStrap.Nav.Link>
                        {/* <ReactBootStrap.Nav.Link href="/contact-us">تواصل معنا</ReactBootStrap.Nav.Link> */}
                        <Link to="/ahed/contact-us" className="nav-link">تواصل معنا</Link>
                        {/* <ReactBootStrap.Button variant="outline-primary">  تبرع الآن</ReactBootStrap.Button> */}
                        <Link to="/ahed/donate">
                            <Button variant="outline-primary" color="primary">
                                تبرع الآن
                            </Button>
                        </Link>
                    </ReactBootStrap.Nav>
                </ReactBootStrap.Navbar.Collapse>
                <ReactBootStrap.Navbar.Collapse id="basic-navbar-nav">
                    <ReactBootStrap.Nav className="mr-auto">
                        <ReactBootStrap.Nav.Link href="#home">تسجيل</ReactBootStrap.Nav.Link>
                        <ReactBootStrap.Nav.Link href="#link">اشتراك</ReactBootStrap.Nav.Link>
                    </ReactBootStrap.Nav>
                </ReactBootStrap.Navbar.Collapse>
            </ReactBootStrap.Navbar>
        </div >
    );
}
export default Navbar;
