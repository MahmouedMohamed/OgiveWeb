import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import OgiveMainPage from './components/OgiveMainPage';
import MainPage from './pets/MainPage';

import Login from './components/Login/Login';
import Register from './components/Register';

import PetDetails from './pets/PetDetails';
import Ahed from './Ahed/Ahed';
function NoMatch() {
    return (
        <div>
            <h3>
                No match for to this Link
            </h3>
        </div>
    );
}
function App() {
    return (
        <div>
            {/* Ogive header */}
            <Router>
                <Switch>
                    <Route exact path="/">
                        <OgiveMainPage />
                    </Route>
                    <Route exact path="/login">
                        <Login />
                    </Route>
                    <Route exact path="/register">
                        <Register />
                    </Route>
                    <Route exact path="/pets">
                        <MainPage />
                    </Route>
                    {/* Ahed Pages */}
                    <Route exact path="/ahed/ahed">
                        <Ahed />
                    </Route>
                    <Route path="/ahed/about">
                        <Ahed />
                    </Route>
                    <Route path="/ahed/needie/:id">
                        <Ahed />
                    </Route>
                    <Route path="/ahed/contact-us">
                        <Ahed />
                    </Route>
                    <Route path="/ahed/join-us">
                        <Ahed />
                    </Route>
                    <Route path="/ahed/donate">
                        <Ahed />
                    </Route>
                    {/* Move them inside /Pets but didnt work(try later) */}
                    <Route path="/pets/pet/:id" component={PetDetails} />
                    <Route path="*">
                        <NoMatch />
                    </Route>
                </Switch>

            </Router>
        </div>

    );
}

export default App;

if (document.getElementById('root')) {
    ReactDOM.render(<App />, document.getElementById('root'));
}
