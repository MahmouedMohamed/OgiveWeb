import React from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter, Route, Switch } from 'react-router-dom'
// import Footer from '../components/Includes/Footer';
import Navbar from './Includes/Navbar';
import Cases from './Cases';
function Ahed() {
    return (
        <BrowserRouter>
            <Switch>
                {/* <Navbar /> */}
                <Cases />
                {/* <Footer /> */}
            </Switch>
        </BrowserRouter >
    );
}

export default Ahed;

// if (document.getElementById('app')) {
//     ReactDOM.render(<Ahed />, document.getElementById('app'));
// }

