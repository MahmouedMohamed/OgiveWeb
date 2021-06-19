import React, { useState, useEffect } from 'react'
// import Header from './Header';
import { Container, Grid, Card, CardMedia, CardContent, CardActions, Typography, Button } from '@material-ui/core';
import { makeStyles } from '@material-ui/core/styles';
import { set } from 'lodash';
import Footer from '.././components/Includes/Footer';
import WelcomePage from './WelcomePage';
import { BrowserRouter, Route, Switch, Link, Redirect } from 'react-router-dom';
import PetDetails from './PetDetails';
import Content from "./Content";

// const cards = [1, 2, 3, 4, 5, 6, 7, 8, 9];
const useStyles = makeStyles((theme) => ({
    icon: {
        marginRight: theme.spacing(2),
    },
    heroContent: {
        backgroundColor: theme.palette.background.paper,
        padding: theme.spacing(8, 0, 6),
    },
    heroButtons: {
        marginTop: theme.spacing(4),
    },
    cardGrid: {
        paddingTop: theme.spacing(8),
        paddingBottom: theme.spacing(8),
    },
    card: {
        height: '100%',
        display: 'flex',
        flexDirection: 'column',
    },
    cardMedia: {
        paddingTop: '56.25%', // 16:9
    },
    cardContent: {
        flexGrow: 1,
    },
    footer: {
        backgroundColor: theme.palette.background.paper,
        padding: theme.spacing(6),
    },
}));
function MainPage() {
    const classes = useStyles();
    const [pets, setPets] = useState([]);
    useEffect(() => {
        fetch("http://127.0.0.1:8000/api/pets")
            .then(data => {
                return data.json();
            })
            .then(data => {
                setPets(data.data);
                console.log(data.data);
            })
            .catch(err => {
                console.log(123123);
            });
    }, []);

    return (
        <div>
            <nav className="navbar navbar-expand-lg navbar-light bg-light">
                {/* <a class="navbar-brand" href="#">Breed Me</a> */}
                <a className="navbar-brand" href="#">
                    <img src="img/adopt_icn.jpg" className="d-inline-block align-top" alt="" />
                </a>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarNav">
                    <ul className="navbar-nav">
                        <li className="nav-item active">
                            <a className="nav-link" href="/pets">الصفحة الرئيسية <span className="sr-only">(current)</span></a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="#">عن Breed Me</a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="#">أخبار و أحداث</a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="#">تواصل معنا</a>
                        </li>
                    </ul>
                </div>
            </nav>
            {/* <WelcomePage /> */}
            {/* <BrowserRouter> */}

            <Content>
                <Switch>
                    <Route path="/pets/pet" component={PetDetails} />
                </Switch>

            </Content>

            {/* </BrowserRouter> */}
            <Container className={classes.cardGrid} maxWidth="md" id="pets">
                <Grid container spacing={4}>
                    {pets.map((pet) => (
                        <Grid item key={pet} xs={12} sm={6} md={4}>
                            <Card className={classes.card}>
                                <CardMedia
                                    className={classes.cardMedia}
                                    image="https://source.unsplash.com/random"
                                    title="Image title"
                                />
                                <CardContent className={classes.cardContent}>
                                    <Typography gutterBottom variant="h5" component="h2">
                                        {pet.name}
                                    </Typography>
                                    <Typography>
                                        This is a media card. You can use this section to describe the content.
                                    </Typography>
                                </CardContent>
                                <CardActions>
                                    <Link to={`/pets/pet/${pet.id}`} >
                                        <Button size="small" color="primary">
                                            مشاهدة
                                        </Button>
                                    </Link>
                                    <Button size="small" color="primary">
                                        تعديل
                                    </Button>
                                </CardActions>
                            </Card>
                        </Grid>
                    ))}
                </Grid>
            </Container>
            <Footer />
        </div >
    )
}
export default MainPage;
