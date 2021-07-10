import React from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter, Route, Switch, Link } from 'react-router-dom'
// import Footer from '../components/Includes/Footer';
import Navbar from './Includes/Navbar';
import Cases from './Cases';
import AboutAhed from './AboutAhed';
import Content from './Content';
import Needie from './Needie';
import ContactUs from './ContactUs';
import DonationForm from './DonationForm';
import Nav from 'react-bootstrap/Nav';
import { Provider, Heading, Subhead } from 'rebass';
import {
    Hero, CallToAction, ScrollDownIndicator
} from 'react-landing-page';
function Ahed() {
    return (
        <React.Fragment>
            <Navbar />
            <Hero
                color="black"
                // bg="white"
                backgroundImage="/img/gofundme_homepage_illustration_959.png"
            >
                <Heading>عهد</Heading>
                <Subhead>تبرع من اليوم</Subhead>
                <CallToAction href="#cases" mt={3}>ابدأ في عهد</CallToAction>
                <ScrollDownIndicator />
            </Hero>
            {/* <nav className="navbar navbar-expand-lg navbar-light bg-light">
                <a className="navbar-brand" href="#">
                    <img src="img/adopt_icn.jpg" className="d-inline-block align-top" alt="" />
                </a>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarNav">
                    <ul className="navbar-nav">
                        <li className="nav-item active">
                            <Nav.Link as={Link} to="/ahed/ahed">الصفحة الرئيسية</Nav.Link>
                        </li>
                        <li className="nav-item">
                            <Nav.Link as={Link} to="/ahed/about">عن عهد</Nav.Link>
                        </li>
                    </ul>
                </div>
            </nav> */}
            <Switch>
                {/* <Content> */}
                <Route path="/ahed/ahed">
                    <Cases />
                </Route>
                <Route path="/ahed/about">
                    <AboutAhed />
                </Route>
                <Route path="/ahed/contact-us">
                    <ContactUs />
                </Route>
                <Route path="/ahed/needie/:id" component={Needie}>
                </Route>
                <Route exact path="/ahed/donate">
                    <DonationForm />
                </Route>
                {/* </Content> */}
            </Switch>
        </React.Fragment>

    );
}

export default Ahed;

// if (document.getElementById('app')) {
//     ReactDOM.render(<Ahed />, document.getElementById('app'));
// }

