import React from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter, Route, Switch } from 'react-router-dom'
import OgiveMainPage from './components/OgiveMainPage';
import Navbar from './components/Navbar';

function App() {
    return (
        <div>
            {/* Ogive header */}
            <BrowserRouter>
                <Navbar />

                <Switch>
                    <Route exact path="/">
                        <OgiveMainPage />
                    </Route>
                </Switch>

            </BrowserRouter>
        </div>

    );
}

export default App;

if (document.getElementById('root')) {
    ReactDOM.render(<App />, document.getElementById('root'));
}
