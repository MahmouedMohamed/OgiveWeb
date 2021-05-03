import React from 'react'
import Form from 'react-bootstrap/Form';
import Button from 'react-bootstrap/Button';
import Box from '@material-ui/core/Box';
import {
    Container,
    Typography,

} from '@material-ui/core';
import { Row, Col, Card } from "react-bootstrap";

function DonationForm() {

    return (
        <div className="text-right">
            <Card>
                <Container>
                    <Card.Title>
                        تبرع
                    </Card.Title>
                    <Form>
                        <Form.Group controlId="formBasicEmail">
                            <Form.Label className="text-right start">الاسم </Form.Label>
                            <Form.Control type="text" />

                        </Form.Group>

                        <Form.Group controlId="formBasicPassword">
                            <Form.Label>البريد الالكتروني</Form.Label>
                            <Form.Control type="email" />
                        </Form.Group>

                        <Button variant="primary" type="submit">
                            Submit
                     </Button>
                    </Form>
                </Container>

            </Card>
        </div>
    );
}

export default DonationForm;