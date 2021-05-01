import React from 'react';
import ReactDOM from 'react-dom';
import * as ReactBootStrap from 'react-bootstrap';
import { BrowserRouter as Router, Switch, Route, Link } from 'react-router-dom';

function Navbar() {
    return (
        <div>
            <ReactBootStrap.Navbar bg="light" expand="lg">
                <ReactBootStrap.Navbar.Brand href="#home"><i className="fab fa-github fa-lg	"></i></ReactBootStrap.Navbar.Brand>
                <ReactBootStrap.Navbar.Toggle aria-controls="basic-navbar-nav" />
                <ReactBootStrap.Navbar.Collapse id="basic-navbar-nav">
                    <ReactBootStrap.Nav className="ml-auto">
                        {/* <ReactBootStrap.Nav.Link to='/home'>Section 1</ReactBootStrap.Nav.Link> */}
                        <Link to="/ahed" className="nav-link">الصفحة الرئيسية</Link>
                        <Link to="/about" className="nav-link">عن عهد</Link>
                        <ReactBootStrap.Nav.Link href="">التبرعات</ReactBootStrap.Nav.Link>
                        <ReactBootStrap.Nav.Link href="#link">تطوع</ReactBootStrap.Nav.Link>
                        <ReactBootStrap.Nav.Link href="#link">تواصل معنا</ReactBootStrap.Nav.Link>
                        <ReactBootStrap.Button variant="outline-success">  تبرع الآن</ReactBootStrap.Button>

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
