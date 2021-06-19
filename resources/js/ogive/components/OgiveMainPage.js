import React from 'react';
import Projects from './Projects';
import Intro from './Intro';
import Navbar from './Includes/Navbar';
import Footer from './Includes/Footer';
class OgiveMainPage extends React.Component {

    render() {
        return (
            <div>
                <Navbar />
                <div className="container">
                    <Intro />
                    <Projects />
                </div>
                <Footer />
            </div>
        );

    }

}
export default OgiveMainPage;
