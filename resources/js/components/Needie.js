import axios from 'axios';
import React from 'react';
import { useState } from 'react';

class Needie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.match.params.id
        }
    }
    loadNeedie() {
        let id = this.state.id;
        let url = 'http://127.0.0.1:8000/api/ahed/needies/' + id;
        axios.get(url)
            .then((response) => {
                console.log(url)
                this.setState({
                    needies: response.data.data.data,
                })
            })
            .catch(error => {
                console.log(error)
            })
    }
    componentDidMount() {
        this.loadNeedie()

    }
    render() {
        return (
            <div>
                wtf
            </div >
        );

    }

}
export default Needie;