import axios from 'axios';
import React from 'react';
import ReactDOM from 'react-dom';
import { Table, Button } from 'reactstrap';
import Navbar from './Navbar';
import {
    Grid, Typography
} from '@material-ui/core';
import useStyles from './styles';
import ChildComponent from './ChildComponent';
import { Row, Col, Card, Container } from "react-bootstrap";
import "./style.css";

class Home extends React.Component {
    constructor() {
        super();
        this.state = {
            needies: [],
            current_page: 1,
            total: 1,
            per_page: 1,
        }
    }
    loadNeedies() {
        let url = 'http://127.0.0.1:8000/api/ahed/needies';
        axios.get(url)
            .then((response) => {
                console.log(response.data.data.data)

                this.setState({
                    needies: response.data.data.data,
                    current_page: response.data.data.current_page,
                    total: response.data.data.total,
                    per_page: response.data.data.per_page,

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
                <section className="hero">
                    <article>
                        <Typography variant="h2" className="text-center">التملُّك الكامل لا يثبته إلا العطاء، فكل ما لا تستطيع إعطاءه يتملكك.
                        </Typography>
                    </article>
                </section>
                <Container>

                    <Row >
                        <Col xs={12} md={10}>
                            <ChildComponent data={this.state.needies} />
                        </Col>
                        <Col xs={6} md={2}>
                            <Card style={{ width: '18rem' }} className="contact-card">
                                <Card.Body>
                                    <i className="fas fa-hand-holding-medical"></i>
                                    <Card.Title className="text-center">يمكنك التبرع من خلال الخط الساخن </Card.Title>
                                    <Card.Text className="text-center bold">
                                        12345
                                    </Card.Text>
                                    <footer className="text-center blockquote-footer pb-4">
                                        ليصلك مندوبنا لحد باب البيت
                                    </footer>
                                </Card.Body>
                            </Card>

                            <Card style={{ width: '18rem' }} className="contact-card">
                                <Card.Body>
                                    <i className="fas fa-hand-holding-medical"></i>
                                    <Card.Title className="text-center">للتبرع من خلال الرسائل</Card.Title>
                                    <Card.Text className="text-center bold">
                                        أرسل رسالة ل 12345 بكلمة "قلب" للتبرع بـ 5 جنيه
                                    </Card.Text>
                                </Card.Body>
                            </Card>
                        </Col>
                    </Row>
                </Container>
            </div>
        );

    }

}
export default Home;
// if (document.getElementById('home')) {
//     ReactDOM.render(<Home />, document.getElementById('home'));
// }