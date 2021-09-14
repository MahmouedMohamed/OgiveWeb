import React from 'react';
import './Login.css';
// import Login from './Login.module.scss';
import Navbar from '../Includes/Navbar';
import Footer from '../Includes/Footer';
function handleSubmit(){
   return alert("sss");
}
function Login() {
    return (
        <>
            <Navbar />
            <div className="login">
                <React.Fragment>
                    <div className="d-flex justify-content-center h-100">
                        <div className="card" id="loginForm">
                            <div class="card-header">
                                <h3>Login</h3>
                                {/* <div class="d-flex justify-content-end social_icon">
                                <span><i className="fab fa-facebook-square"></i></span>
                                <span><i className="fab fa-google-plus-square"></i></span>
                                <span><i className="fab fa-twitter-square"></i></span>
                            </div> */}
                            </div>
                            <div className="card-body">
                                <form onSubmit={handleSubmit()}>
                                    <div class="input-group form-group">
                                        <div className="input-group-prepend">
                                            <span className="input-group-text"><i className="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" className="form-control" placeholder="username" />

                                    </div>
                                    <div class="input-group form-group">
                                        <div className="input-group-prepend">
                                            <span className="input-group-text"><i className="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" className="form-control" placeholder="password" />
                                    </div>
                                    <div className="row align-items-center remember">
                                        <input type="checkbox" />Remember Me
                                    </div>
                                    <div className="form-group">
                                        <input type="submit" value="Login" className="btn float-right login_btn" />
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <div className="d-flex justify-content-center links">
                                    Don't have an account?<a href="#">Sign Up</a>
                                </div>
                                <div className="d-flex justify-content-center">
                                    <a href="#">Forgot your password?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </React.Fragment>
            </div>
            <Footer />
        </>
    )
}
export default Login;
