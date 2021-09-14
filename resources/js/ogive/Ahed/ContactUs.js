import React from 'react'
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Box from '@material-ui/core/Box';
import { shadows } from '@material-ui/system';
import Image from 'react-bootstrap/Image'
import {
    Container,
    Typography,
    Grid,
    TextField

} from '@material-ui/core';
import { Row, Col, Card } from "react-bootstrap";
const ContactUs = () => {
    return (
        <Container className="text-right">
            <Box p={5} boxShadow={3}>
                <Grid container>
                    <Grid item md={6}>
                        <Typography variant="h5">
                            دعنا نتحدث عن إقتراحك
                        </Typography>
                        <Typography m={2}>
                            عهد تيتح لك اضافة حالات تحتاج الي مساعدة
                        </Typography>
                        <Form>
                            <Form.Group controlId="formBasicName">
                                <Form.Label>الاسم </Form.Label>
                                <Form.Control type="text" placeholder="إدخل اسمك" className="input" required />
                            </Form.Group>
                            <Form.Group controlId="formBasicEmail">
                                <Form.Label>البريد الالكتروني </Form.Label>
                                <Form.Control type="email" placeholder="البريد الالكتروني" className="text-right input" required />
                            </Form.Group>
                            <Form.Group controlId="formBasicPhone">
                                <Form.Label>رقم الهاتف</Form.Label>
                                <Form.Control type="text" placeholder="+20" className="input" required />
                            </Form.Group>
                            <fieldset>
                                <Form.Group as={Row} className="mb-3">
                                    <Form.Label as="legend" column sm={2}>
                                    نوع الاستفسار
                                    </Form.Label>
                                    <Col sm={10}>
                                        <Form.Check
                                            type="radio"
                                            label="استعلام"
                                            name="formHorizontalRadios"
                                            id="formHorizontalRadios1"
                                        />
                                        <Form.Check
                                            type="radio"
                                            label="الشكوى والاقتراح"
                                            name="formHorizontalRadios"
                                            id="formHorizontalRadios2"
                                        />
                                    </Col>
                                </Form.Group>
                            </fieldset>
                            {/* <Form.Group controlId="formGridState">
                                <Form.Label>المدينة</Form.Label>
                                <Form.Select defaultValue="Choose...">
                                    <option>Choose...</option>
                                    <option>القاهرة</option>
                                    <option>اسكندرية</option>
                                    <option>اسيوط</option>
                                </Form.Select>
                            </Form.Group> */}
                            <Form.Group controlId="formBasicSubject">
                                <Form.Label>الموضوع </Form.Label>
                                <Form.Control type="text" placeholder="الموضوع" className="input" required/>
                            </Form.Group>
                            <Form.Group controlId="exampleForm.ControlTextarea1">
                                <Form.Label>رسالتك </Form.Label>
                                <Form.Control as="textarea" rows={3} className="input" required />
                            </Form.Group>

                            <Button variant="primary" type="submit">
                                ارسل
                            </Button>
                        </Form>
                    </Grid>
                    <Grid item md={6}>
                        <Image src="/img/makeachange.jpg" className="image" />
                    </Grid>
                </Grid>

            </Box>
        </Container>

    );
}

export default ContactUs;