import React from 'react';
import ReactDOM from 'react-dom';
import Footer from '../ogive/components/Includes/Footer';
import Navbar from './Navbar';
import Lottie from "lottie-react";
import groovyWalkAnimation from "./../../../public/animations/36395-lonely-404.json";


function Page404() {
    return (
        <div>
            <Navbar />
            <div className="">
                <div class=" mt-5 pt-5">
                    <div class="text-center">
                        <div style={ { height: '40%' } } >
                        <Lottie animationData={groovyWalkAnimation} height={50}
                            width={400} />;
                            </div>
                        <h2 class="display-3">لا يوجد شئ هنا غير الغبار</h2>
                        <p class="display-5"></p>
                    </div>
                </div>
            </div>
            <Footer></Footer>
        </div>
    );
}

export default Page404;
