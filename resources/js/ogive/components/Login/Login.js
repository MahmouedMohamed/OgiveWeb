import React, { useState } from 'react';
import './Login.css';
// import Login from './Login.module.scss';
import Navbar from '../Includes/Navbar';
import Footer from '../Includes/Footer';
import { useHistory } from 'react-router-dom';

function Login() {
    const history = useHistory();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const handleSubmit = (e) => {
        e.preventDefault();
        const user = {
            email: email,
            password: password,
            accessType: "token",
            appType: "mobile"
        };
        axios.post(`/api/login`, user)
            .then(res => {
                localStorage.setItem('token', JSON.stringify(res.data.data.token));
                let token = localStorage.getItem('token');
                token = JSON.parse(token);
                history.push("/");
            })
    }
    return (
        <>
            <Navbar />
            <div className="login">
                <React.Fragment>
                    <div className="d-flex justify-content-center h-100">
                        <div className="card" id="loginForm">
                            <div className="card-header">
                                <h3>تسجيل الدخول</h3>
                            </div>
                            <div className="card-body">
                                <form onSubmit={handleSubmit}>
                                    <div className="input-group form-group">
                                        <div className="input-group-prepend">
                                            <span className="input-group-text"><i className="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" onChange={e => setEmail(e.target.value)} className="form-control" placeholder="البريد الالكتروني" />

                                    </div>
                                    <div className="input-group form-group">
                                        <div className="input-group-prepend">
                                            <span className="input-group-text"><i className="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" onChange={e => setPassword(e.target.value)} className="form-control" placeholder="كلمة المرور" />
                                    </div>
                                    <div className="row align-items-center remember">
                                        <input type="checkbox" />تذكرني
                                    </div>
                                    <div className="form-group">
                                        <input type="submit" value="تسجبل" className="btn float-right login_btn" />
                                    </div>
                                </form>
                            </div>
                            <div className="card-footer">
                                <div className="d-flex justify-content-center links">
                                    لا تمتلك حساب لدينا?<a href="#">انشئ حساب </a>
                                </div>
                                <div className="d-flex justify-content-center">
                                    <a href="#">نسيت كلمة المرور?</a>
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
