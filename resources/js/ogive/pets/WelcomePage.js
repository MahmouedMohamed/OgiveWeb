import React from 'react'
import { Container, Jumbotron } from 'reactstrap'

const WelcomePage = () => {
    return (
        <>
            <p className="text-center">الصفحة الرئيسية</p>
            <header>
                <div className="hero-image">
                    <div className="hero-text">
                        <h1 >مرحبا بك في Breed Me</h1>
                        {/* <h3>We provide care, that your pet deserves</h3> */}
                        <h3>نحن نقدم الرعاية التي يستحقها حيوانك الأليف</h3>
                        <button className="intro-button"> <a href="#pets"> استكشف هنا</a></button>
                    </div>
                </div>
            </header>
        </>

    )
}

export default WelcomePage;
