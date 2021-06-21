import React from 'react'
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Box from '@material-ui/core/Box';
import {
    Container,

} from '@material-ui/core';
import {
    CardActions, Grid, Typography, FormControl,
    InputLabel, OutlinedInput, InputAdornment,
} from '@material-ui/core';
import { Row, Col, Card, Nav } from "react-bootstrap";
import Tab from 'react-bootstrap/Tab';
import Carousel from 'react-bootstrap/Carousel';
import Footer from './Footer';
import Select from 'react-select';
const options = [
    { value: 'chocolate', label: 'Chocolate' },
    { value: 'strawberry', label: 'Strawberry' },
    { value: 'vanilla', label: 'Vanilla' },
];
class DonationForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            needies: [],
        };
        // this.handleChange = this.handleChange.bind(this);
        // this.onSubmit = this.onSubmit.bind(this);
    }

    loadNeedies() {
        let url = "http://127.0.0.1:8000/api/ahed/allNeedies";
        axios
            .get(url)
            .then((response) => {
                this.setState({
                    needies: response.data.data,
                });
                console.log(response.data.data);
            })
            .catch((error) => {
                console.log(error);
            });
    }
    componentDidMount() {
        this.loadNeedies();
    }
    render() {
        // Old way to render in option 

        const renderNeedies = () => {
            let neediesList = [];
            this.state.needies.map((needie, index) => (
                neediesList.push({ label: needie.name, value: needie.id })

            ));
            return neediesList;
        };
        return (
            <div className="text-right" >
                <div className="img">
                    <img src="https://baheya.org/uploads/sliders/196901601478973.png" alt="" />
                </div>
                <Tab.Container id="left-tabs-example" defaultActiveKey="first">
                    <Row>
                        <Col sm={3}>
                            <Nav variant="pills" className="flex-column">
                                <Nav.Item>
                                    <Nav.Link eventKey="first">التبرع عن خلال حساب البنك</Nav.Link>
                                </Nav.Item>
                                <Nav.Item>
                                    <Nav.Link eventKey="second"> التبرع عن خلال المنزل</Nav.Link>
                                </Nav.Item>
                            </Nav>
                        </Col>
                        <Col sm={9}>
                            <Tab.Content>
                                <Tab.Pane eventKey="first">
                                    <Card>
                                        <Container>
                                            <Card.Title>
                                                تبرع اونلاين
                                            </Card.Title>
                                            <Form>
                                                <Card.Header className="text-center font-weight-bold user-info">
                                                    البيانات الحالة
                                                </Card.Header>
                                                <Form.Group controlId="formBasicEmail">
                                                    <Form.Label className="text-right start">الحالة </Form.Label>
                                                    <Select options={renderNeedies()} />
                                                </Form.Group>
                                                <Form.Group fullWidth variant="outlined " className="text-right">
                                                    <Form.Label className="text-right start">المبلغ </Form.Label>
                                                    <Form.Control type="number" placeholder="المبلغ المتبرع به" />
                                                </Form.Group>
                                                <Card.Header className="text-center font-weight-bold user-info">
                                                    البيانات الشخصية
                                                </Card.Header>
                                                <Form.Group controlId="formBasicEmail">
                                                    <Form.Label className="text-right start">الاسم </Form.Label>
                                                    <Form.Control type="text" />
                                                </Form.Group>

                                                <Form.Group controlId="formBasicPassword">
                                                    <Form.Label>البريد الالكتروني</Form.Label>
                                                    <Form.Control type="email" />
                                                </Form.Group>
                                                <Card.Header className="text-center font-weight-bold user-info">
                                                    بيانات بطاقة الدفع
                                                </Card.Header>
                                                <div class="form-group">
                                                    <label for="username">Full name (on the card)</label>
                                                    <input type="text" name="username" placeholder="Jassa" required class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="cardNumber">رقم البطاقة</label>
                                                    <div class="input-group">
                                                        <input type="text" name="cardNumber" placeholder="Your card number" class="form-control" required />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text text-muted">
                                                                <i class="fa fa-cc-visa mx-1"></i>
                                                                <i class="fa fa-cc-amex mx-1"></i>
                                                                <i class="fa fa-cc-mastercard mx-1"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <label><span class="hidden-xs">Expiration</span></label>
                                                            <div class="input-group">
                                                                <input type="number" placeholder="MM" name="" class="form-control" required />
                                                                <input type="number" placeholder="YY" name="" class="form-control" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group mb-4">
                                                            <label data-toggle="tooltip" title="Three-digits code on the back of your card">CVV
                                                            <i class="fa fa-question-circle"></i>
                                                            </label>
                                                            <input type="text" required class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <Button variant="primary" type="submit">
                                                    إتمام
                                            </Button>
                                            </Form>
                                        </Container>
                                    </Card>
                                </Tab.Pane>
                                <Tab.Pane eventKey="second">
                                    <Card>
                                        <Container>
                                            <Card.Title>
                                                تبرع من منزلك
                                            </Card.Title>
                                            <Card.Header className="text-center font-weight-bold user-info">
                                                البيانات الحالة
                                                </Card.Header>
                                            <Form.Group controlId="formBasicEmail">
                                                <Form.Label className="text-right start">الحالة </Form.Label>
                                                <Select options={renderNeedies()} />
                                            </Form.Group>
                                            <Form.Group fullWidth variant="outlined " className="text-right">
                                                <Form.Label className="text-right start">المبلغ </Form.Label>
                                                <Form.Control type="number" placeholder="المبلغ المتبرع به" />
                                            </Form.Group>
                                            <Card.Header className="text-center font-weight-bold user-info">
                                                البيانات الشخصية
                                                </Card.Header>
                                            <Card.Text>
                                                سجل بياناتك و هيوصلك مندوبنا
                                            </Card.Text>
                                            <Form>
                                                <Form.Group controlId="formBasicEmail">
                                                    <Form.Label className="text-right start">الاسم </Form.Label>
                                                    <Form.Control type="text" />
                                                </Form.Group>
                                                <Form.Group controlId="formBasicPassword">
                                                    <Form.Label>البريد الالكتروني</Form.Label>
                                                    <Form.Control type="email" />
                                                </Form.Group>
                                                <Form.Group controlId="formBasicPassword">
                                                    <Form.Label> العنوان</Form.Label>
                                                    <Form.Control type="textarea" />
                                                </Form.Group>
                                                <Button variant="primary" type="submit">
                                                    إتمام
                                            </Button>
                                            </Form>
                                        </Container>
                                    </Card>
                                </Tab.Pane>

                            </Tab.Content>
                        </Col>
                    </Row>
                </Tab.Container>
                <Footer />

            </div >
        );
    }


}

export default DonationForm;