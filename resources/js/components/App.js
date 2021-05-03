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

function App() {
    return (
        <BrowserRouter>
            <Navbar />
            <Switch>
                <Route exact path="/ahed">
                    <Home />
                </Route>
                <Route exact path="/about">
                    <About />
                </Route>
                <Route exact path="/donate/:id">
                    <DonationForm />
                </Route>
                <Route exact path="/donate">
                    <DonationForm />
                </Route>
                <Route exact path="/needie/:id" component={Needie} />
                <Route exact path="/contact-us" component={ContactUs} />

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
