import React from 'react';
import { Grid, Paper, Typography, Box } from '@material-ui/core';
import "./style.css";

function About() {
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-12">
                    {/* <div className="card"> */}
                    {/* <div className="card-header">عن عهد </div> */}
                    <Typography variant="h1" align="center" className="primary-color">مهمة عهد
                    </Typography>
                    <Grid container spacing={3}>
                        <Grid item xs={4} align="center">
                            <img src="https://www.baheya.org/front/img/vision.png" loading="lazy" class="rounded-circle" />
                            <Typography variant="h4" align="center">رؤيتنا</Typography>
                            <p>مؤسسة بهية هي المقصد الأول لصحة وسلامة المرأة</p>
                        </Grid>
                        <Grid item xs={4} align="center">
                            <img src="https://www.baheya.org/front/img/mission.png" loading="lazy" class="rounded-circle" />
                            <Typography variant="h4" align="center">رسالتنا</Typography>
                            <p>مؤسسة بهية هي المقصد الأول لصحة وسلامة المرأة</p>

                        </Grid>
                        <Grid item xs={4} align="center">
                            <img src="https://www.baheya.org/front/img/goal.png" loading="lazy" class="rounded-circle" />
                            <Typography variant="h4" align="center">هدفنا</Typography>
                            <p>مؤسسة بهية هي المقصد الأول لصحة وسلامة المرأة</p>
                        </Grid>
                    </Grid>
                    <Typography variant="h2" align="center" className="primary-color">لماذا عهد؟</Typography>
                    <Grid container spacing={3}>
                        <Grid item xs={6}>
                            <Paper>
                                <img src="/img/follow.png" loading="lazy" width="500" height="267" class="rounded" />
                            </Paper>
                        </Grid>
                        <Grid item xs={6} className="text-center">
                            {/* <Paper> */}
                            <Box my={7}>
                                <Typography variant="h4" >
                                    يمكن للمستخدمين إضافة طلبات لإنشاء الحالات بجانب مسئولي التطبيق، حيث يمكنهم إضافة البيانات الخاصة بالحالة كالإسم و السن و العنوان و بعض التفاصيل حتي يتسني للمسئولين عن التطبيق مراجعة تلك البيانات و التأكد من صحتها من خلال زيارة تتم من خلالهم
                                    بالطبع هذة الطلبات لن تظهر للمستخدمين الأخرين إلا بعض التأكد من صحة هذة الحالة و نشرها
                                </Typography>
                            </Box>
                            {/* </Paper> */}
                        </Grid>
                        <Grid item xs={6}>
                            <Paper>
                                <img src="/img/descover.png" loading="lazy" width="500" height="267" class="rounded" />
                            </Paper>
                        </Grid>
                        <Grid item xs={6} className="text-center">
                            {/* <Paper> */}
                            <Box my={7}>
                                <Typography variant="h4" >
                                    تابع الحالات اللتي تحتاج مساعدتك الموجودة في عهد
                                </Typography>
                            </Box>
                            {/* </Paper> */}
                        </Grid>
                        <Grid item xs={6}>
                            <Paper>
                                <img src="/img/meetpeople.png" loading="lazy" width="500" height="267" class="rounded" />
                            </Paper>
                        </Grid>
                        <Grid item xs={6} className="text-center">
                            {/* <Paper> */}
                            <Box my={7}>
                                <Typography variant="h4" >
                                    يمكنك التبرع في مقر عهد
                                </Typography>
                            </Box>
                            {/* </Paper> */}
                        </Grid>
                    </Grid>
                    {/* </div> */}
                </div>
            </div>
        </div >
    );
}

export default About;
