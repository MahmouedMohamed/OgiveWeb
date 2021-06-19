import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter, Route, Switch } from 'react-router-dom';
import OgiveMainPage from './components/OgiveMainPage';
import MainPage from './pets/MainPage';

import Login from './components/Login/Login';
import Register from './components/Register';

import PetDetails from './pets/PetDetails';
import Ahed from './Ahed/Ahed';
function App() {
    return (
        <div>
            {/* Ogive header */}
            <BrowserRouter>
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
                    <Route path="/ahed/ahed">
                        {/* Ahed */}
                        <Ahed />
                    </Route>
                    {/* Move them inside /Pets but didnt work(try later) */}
                    <Route path="/pets/pet/:id" component={PetDetails} />

                </Switch>

            </BrowserRouter>
        </div>

    );
}

export default App;

if (document.getElementById('root')) {
    ReactDOM.render(<App />, document.getElementById('root'));
}
