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
                    <Grid item xs={6}>
                        <Typography variant="h5">
                            دعنا نتحدث عن إقتراحك
                        </Typography>
                        <Typography m={2}>
                            عهد تيتح لك اضافة حالات تحتاج الي مساعدة
                        </Typography>
                        <Form>
                            <Form.Group controlId="formBasicEmail">
                                <Form.Label>الاسم </Form.Label>
                                <Form.Control type="text" placeholder="إدخل اسمك" className="input" />
                            </Form.Group>

                            <Form.Group controlId="formBasicPhone">
                                <Form.Label>رقم الهاتف</Form.Label>
                                <Form.Control type="text" placeholder="+20" className="input" />
                            </Form.Group>

                            <Form.Group controlId="formBaicAddress">
                                <Form.Label> العنوان</Form.Label>
                                <Form.Control type="text" placeholder="شارع , منطقة , محافظة" className="input" />
                            </Form.Group>
                            <Form.Group controlId="exampleForm.ControlTextarea1">
                                <Form.Label>رسالتك </Form.Label>
                                <Form.Control as="textarea" rows={3} className="input" />
                            </Form.Group>

                            <Button variant="primary" type="submit">
                                Submit
                            </Button>
                        </Form>
                    </Grid>
                    <Grid item>
                        {/* <Image src="/img/makeachange.jpg" width={50} />
                         */}
                        {/* <div class="row px-3 justify-content-center mt-4 mb-5 border-line"> */}
                        <Image src="/img/makeachange.jpg" className="image" />
                        {/* </div> */}
                    </Grid>


                </Grid>

            </Box>
        </Container>

    );
}

export default ContactUs;