import React from 'react';
import {
    Container,
    Typography,
    Grid,
    TextField

} from '@material-ui/core';
function JoinUs() {
    return (
        <React.Fragment>
            <Container className="text-right pt-3">
                <div class="section" id="pricing">
                    <div class="container">
                        <div class="section-title">
                            <h3>إنضم الينا</h3>
                        </div>
                        <div class="card-deck">
                            <div class="card pricing">
                                <div class="card-head">
                                <img
                                        src="https://mdbootstrap.com/img/new/standard/city/062.jpg"
                                        className="card-img-top"
                                    />
                                </div>
                                <div class="card-body">
                                    <a href="#" class="btn btn-primary btn-lg btn-block">التطوع</a>
                                </div>
                            </div>
                            <div class="card pricing">
                                <div class="card-head">
                                    <img
                                        src="https://mdbootstrap.com/img/new/standard/city/062.jpg"
                                        className="card-img-top"
                                    />
                                </div>
                                <div class="card-body">
                                    <a href="/ahed/join-us/charities" class="btn btn-primary btn-lg btn-block">الجمعيات الخيرية</a>
                                </div>
                            </div>
                            <div class="card pricing">
                                <div class="card-head">
                                <img
                                        src="https://mdbootstrap.com/img/new/standard/city/062.jpg"
                                        className="card-img-top"
                                    />
                                </div>
                                <div class="card-body">
                                    <a href="#" class="btn btn-primary btn-lg btn-block">الموردين</a>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </Container>
        </React.Fragment>

    );
}
export default JoinUs;