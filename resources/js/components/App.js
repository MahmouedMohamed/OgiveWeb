import React from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter, Route, Switch } from 'react-router-dom'
import Navbar from './Navbar';
import Home from './Home';
import Example from './Example'
import NotFoundPage from './NotFoundPage'
import About from './About'
import DonationForm from './DonationForm';
import Needie from './Needie';
import ContactUs from './ContactUs';
import { ProtectedRoute } from "./includes/protected.route";

function App() {
    return (
        <BrowserRouter>
            <Navbar />
            <Switch>
                <Route exact path="/ahed">
                    {/* Temp Page */}
                    <About />
                </Route>
                <Route exact path="/ahed/ahed">
                    <Home />
                </Route>
                <Route exact path="/ahed/about">
                    <About />
                </Route>
                <Route exact path="/ahed/donate/:id">
                    <DonationForm />
                </Route>
                <ProtectedRoute exact path="/ahed/donate">
                    <DonationForm />
                </ProtectedRoute>
                <Route exact path="/ahed/needie/:id" component={Needie} />
                <Route exact path="/ahed/contact-us" component={ContactUs} />

                {/* <Route path='/:id' component={SingleProject} /> */}

            </Switch>

            {/* <Switch>
                <Route exact path="/">
                    <Example />
                </Route>
                <Route exact path="/home">
                    <Home />
                </Route>
                <Route exact path="/about">
                    <About />
                </Route>
            </Switch> */}
        </BrowserRouter>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
