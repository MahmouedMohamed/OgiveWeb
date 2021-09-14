import React, { memo } from 'react'

import { Container, Row, Col } from 'react-bootstrap';

function Intro() {
    return (
        <React.Fragment>
            <Container>
                <div className="page-header header-filter bg-img" data-parallax="true">
                    <div className="container">
                        <div className="row">
                            <div className="col-md-6">
                                <h1 className="title">قصتك تبدأ معنا</h1>
                                {/* <h4>Ogive helps you to leave a beautiful effect on others, You don't have to be a doctor to help others</h4> */}
                                <h4>Ogive تساعدك لترك اٌثر عند غيرك</h4>
                                <a href="#" target="_blank" className="btn btn-danger btn-raised btn-lg">
                                    {/* VIDEO ABOUT US */}
                                    <i className="fa fa-play"></i> شاهد فيديو
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </Container>
        </React.Fragment>
    );
}

export default Intro;


