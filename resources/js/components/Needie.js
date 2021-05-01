import axios from 'axios';
import React from 'react';
import ReactDOM from 'react-dom';
import { Table, Button } from 'reactstrap';
import Navbar from './Navbar';
import {
    Container,
    Typography,
    Grid,
    Card,
    CardActions,
    CardContent,
    CardMedia,

} from '@material-ui/core';
import ChildComponent from './ChildComponent';
class Needie extends React.Component {
    constructor() {
        super();
        this.state = {
            needies: [],
        }
    }
    loadNeedies() {
        let url = 'http://127.0.0.1:8000/api/ahed/needies';
        axios.get(url)
            .then((response) => {
                console.log(response.data.data.data)
                this.setState({
                    needies: response.data.data.data,
                })
            })
            .catch(error => {
                console.log(error)
            })
    }
    componentDidMount() {
        this.loadNeedies()
    }
    render() {
        let needies = this.state.needies.map((needie) => {
            return (
                <tr key={needie.id}>
                    <td>{needie.id}</td>
                    <td>{needie.name}</td>
                </tr>
            )
        })
        return (
            <div>
                <div className="container">
                    <Typography variant="h3" className="text-center">التملُّك الكامل لا يثبته إلا العطاء، فكل ما لا تستطيع إعطاءه يتملكك.
                </Typography>
                    <ChildComponent data={this.state.needies} />
                </div>


            </div >
        );

    }

}
export default Needie;