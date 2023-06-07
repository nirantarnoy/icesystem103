<?php

namespace frontend\modules\api\controllers;

use yii\filters\VerbFilter;
use yii\web\Controller;


class AuthenController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['POST'],
                    'loginqrcode' => ['POST'],
                    'orderlist' => ['POST']
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        $username = '';
        $password = '';
        $status = false;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$post = file_get_contents("php://input");
        $req_data = \Yii::$app->request->getBodyParams();
        if ($req_data != null) {
            $username = $req_data['username'];
            $password = $req_data['password'];
        }
        $data = [];
        if ($username != '' && $password != '') {
            $model = \common\models\User::find()->where(['username' => $username])->one();
            if ($model) {
                if ($model->validatePassword($password)) {
                    $model_info = \backend\models\Employee::find()->where(['id' => $model->employee_ref_id])->one();
                    if ($model_info) {
                        $car_info = $this->getCar($model_info->id, $model->company_id, $model->branch_id);
                        $member_id = 0;

                        array_push($data, [
                                'username' => $username,
                                'user_id' => '' . $model->id,
                                'emp_id' => '' . $model->employee_ref_id, // person 1
                                'emp2_id' => '' . $member_id, // person 2
                                'emp_code' => $model_info->code,
                                'emp_name' => $model_info->fname . ' ' . $model_info->lname,
                                'emp_photo' => 'http://103.253.73.108/icesystem/backend/web/uploads/images/employee/' . $model_info->photo,
                                //      'emp_photo' => 'http://192.168.1.120/icesystem/backend/web/uploads/images/employee/' . $model_info->photo,
                                'emp_route_id' => $car_info == null ? 0 : $car_info[0]['route_id'],
                                'emp_route_name' => $car_info == null ? 0 : $car_info[0]['route_name'],
                                'emp_car_id' => $car_info == null ? 0 : $car_info[0]['car_id'],
                                'emp_car_name' => $car_info == null ? 0 : $car_info[0]['car_name'],
                                'company_id' => $model->company_id,
                                'branch_id' => $model->branch_id,
                                'branch_name' => \backend\models\Branch::findName($model->branch_id),
                                'route_type' => $car_info == null ? 1 : \backend\models\Deliveryroute::findRouteType($car_info[0]['route_id']),
                            ]
                        );
                        $status = true;
                    }

                }
            }
        }

        return ['status' => $status, 'data' => $data];
    }

    public function actionLoginqrcode()
    {
        $car = '';
        $driver = '';
        $password = '';
        $member = '';

        $status = 0;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$post = file_get_contents("php://input");
        $req_data = \Yii::$app->request->getBodyParams();
        if ($req_data != null) {
            $car = $req_data['car'];
            $driver = $req_data['driver'];
            $password = $req_data['password'];
            $member = $req_data['member'];
        }
        $data = [];
        if ($car != '' && $driver != '') {
            $find_user_id = \backend\models\Employee::findUserid($driver);
            $model = \common\models\User::find()->where(['id' => $find_user_id])->one();
            if ($model) {
//                //  if ($model->validatePassword($password)) {
                $model_info = \backend\models\Employee::find()->where(['id' => $model->employee_ref_id])->one();
                if ($model_info) {
                    //    $car_info = $this->getCar($model_info->id, $model->company_id, $model->branch_id);
                    $car_info = $this->getCarId($car, $model->company_id, $model->branch_id);
                    if ($car_info) {
                        $member_id = 0;
                        if ($member != null || $member != '') {
                            $find_user_member_id = \backend\models\Employee::findUserid($member);
                            $model_member_user = \common\models\User::find()->where(['id' => $find_user_member_id])->one();
                            if ($model_member_user) {
                                $member_id = $model_member_user->employee_ref_id;
                            }
                        }

                        $emp_id = [];
                        $emp_id[0] = $model->employee_ref_id;
                        $emp_id[1] = $member_id; //$member!=null?\backend\models\Employee::findUserid($member):0;//\backend\models\User::getIdFromUsername($member);

                        $isdriver = [];
                        $isdriver[0] = 1;
                        $isdriver[1] = 0;

                        $has_driver_login = $this->checkHaslogin($model->employee_ref_id, 1);
                        $has_memeber_login = $this->checkHaslogin($member_id, 0);

                        if ($has_driver_login > 0 || $has_memeber_login > 0) { // has today login
                            $status = 0;
                        } else {
                            if ($this->addEmpDaily($car_info[0]['car_id'], $car_info[0]['route_id'], null, $emp_id, $isdriver, $model->company_id, $model->branch_id)) {
                                $status = 1;

                                array_push($data, [
                                        'username' => $driver,
                                        'user_id' => '' . $model->id,
                                        'emp_id' => '' . $model->employee_ref_id, // person 1
                                        'emp2_id' => '' . $member_id, // person 2
                                        'emp_code' => $model_info->code,
                                        'emp_name' => $model_info->fname . ' ' . $model_info->lname,
                                        'emp_photo' => 'http://103.253.73.108/icesystem/backend/web/uploads/images/employee/' . $model_info->photo,
                                        //      'emp_photo' => 'http://192.168.1.120/icesystem/backend/web/uploads/images/employee/' . $model_info->photo,
                                        'emp_route_id' => $car_info == null ? 0 : $car_info[0]['route_id'],
                                        'emp_route_name' => $car_info == null ? 0 : $car_info[0]['route_name'],
                                        'emp_car_id' => $car_info == null ? 0 : $car_info[0]['car_id'],
                                        'emp_car_name' => $car_info == null ? 0 : $car_info[0]['car_name'],
                                        'company_id' => $model->company_id,
                                        'branch_id' => $model->branch_id,
                                        'branch_name' => \backend\models\Branch::findName($model->branch_id),
                                        'route_type' => $car_info == null ? 1 : \backend\models\Deliveryroute::findRouteType($car_info[0]['route_id']),
                                    ]
                                );
                            } else {
                                $status = 0;
                            }
                        }
                    }
                }
//                // }
            }
        }

        return ['status' => $status, 'data' => $data];
    }

    public function checkHaslogin($user_id, $is_driver)
    {
        $res = 0;
        if ($user_id != null || $user_id != '') {
            $model = \backend\models\Cardaily::find()->where(['date(trans_date)' => date('Y-m-d'), 'employee_id' => $user_id])->one();
            if ($model) {
                if ($model->is_driver == $is_driver) {
                    $res = 0;
                } else {
                    $res = 1;
                }

            }

            return $res;
        }
    }

    public function getCar($emp_id, $company_id, $branch_id)
    {
        $data = [];
        if ($emp_id) {
            $model = \common\models\CarDaily::find()->where(['employee_id' => $emp_id, 'date(trans_date)' => date('Y-m-d'), 'company_id' => $company_id, 'branch_id' => $branch_id])->one();
            if ($model) {
                array_push($data, [
                    'car_id' => $model->car_id,
                    'car_name' => \backend\models\Car::findName($model->car_id),
                    'route_id' => \backend\models\Car::findRouteId($model->car_id),
                    'route_name' => \backend\models\Car::findRouteName($model->car_id),
                ]);
            }
        }
        return $data;
    }

    public
    function getCarId($car, $company_id, $branch_id)
    {
        $data = [];
        if ($car != null) {
            $model = \backend\models\Car::find()->where(['code' => trim($car), 'company_id' => $company_id, 'branch_id' => $branch_id])->one();
            if ($model) {
                array_push($data, [
                    'car_id' => $model->id,
                    'car_name' => $model->name,
                    'route_id' => $model::findRouteId($model->id),
                    'route_name' => $model::findRouteName($model->id),
                ]);
            }
        }
        return $data;
    }

    public
    function addEmpDaily($car_id, $route_id, $t_date, $emp_id, $isdriver, $company_id, $branch_id)
    {

        if ($route_id == null || $route_id == '') {
            $route_id = 0;
        }

        if ($t_date == null) {
            $t_date = date('Y-m-d');
        } else {
            $x_date = explode('/', $t_date);
            $x_date2 = null;
            if (count($x_date) > 1) {
                $x_date2 = $x_date[2] . '/' . $x_date[1] . '/' . $x_date[0];
            }
            $t_date = date('Y-m-d', strtotime($x_date2));
        }

        if ($car_id) {
            if ($emp_id != null) {
                //if(\backend\models\Cardaily::deleteAll(['date(trans_date)' => $t_date, 'car_id' => $car_id])){
                for ($i = 0; $i <= count($emp_id) - 1; $i++) {
                    if ($emp_id[$i] == '' || $emp_id[$i] == 0 && $emp_id[$i] == 27) continue; // $emp_id[$i] = 0;
                    if ($this->checkOld($emp_id[$i], $car_id, $t_date, $company_id, $branch_id)) {
                        //echo "has no ";return;
                        $model = \backend\models\Cardaily::find()->where(['date(trans_date)' => $t_date, 'employee_id' => $emp_id[$i], 'car_id' => $car_id])->one();
                        if ($model) {
                            $model->is_driver = $isdriver[$i] == 1 ? 1 : 0;
                            $model->save(false);
                        } else {
                            $model = new \backend\models\Cardaily();
                            $model->car_id = $car_id;
                            $model->employee_id = $emp_id[$i];
                            $model->trans_date = $t_date;
                            $model->is_driver = $isdriver[$i] == 1 ? 1 : 0;
                            $model->status = 1;
                            $model->company_id = $company_id;
                            $model->branch_id = $branch_id;
                            $model->save(false);
                        }

                    } else {
                        // echo "has ";return;
                        $model = new \backend\models\Cardaily();
                        $model->car_id = $car_id;
                        $model->employee_id = $emp_id[$i];
                        $model->trans_date = $t_date;
                        $model->is_driver = $isdriver[$i] == 1 ? 1 : 0;
                        $model->status = 1;
                        $model->company_id = $company_id;
                        $model->branch_id = $branch_id;
                        $model->save(false);
                    }

                }
                // }

            } else {
                return false;
            }
        }
        return true;
    }

    public
    function checkOld($emp_id, $car_id, $t_date, $company_id, $branch_id)
    {
        $model = \backend\models\Cardaily::find()->where(['employee_id' => $emp_id, 'date(trans_date)' => $t_date, 'company_id' => $company_id, 'branch_id' => $branch_id])->count();
//        if ($model>0) {
//            \backend\models\Cardaily::deleteAll(['car_id' => $car_id, 'employee_id' => $emp_id, 'date(trans_date)' => $t_date]);
//        }
        return $model;
    }

//    public function actionLogin()
//    {
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        //$post = file_get_contents("php://input");
//        $req_data = \Yii::$app->request->getBodyParams();
//        $req_data2 = \Yii::$app->getRequest()->getBodyParams();
//
//        $req_data3 = \yii::$app->request->post();
//        // return $params;
//        // print_r($req_data3);
//        $username = $req_data;
//        $password = $req_data3;
//        $data = [];
//        array_push($data, [
//            'username' => 'It is ' . $username,
//            'password' => 'It is ' . $password
//
//        ]);
//        if ($username != '' && $password != '') {
//            $model = \common\models\User::find()->where(['username' => $username])->one();
//            if ($model) {
//                if ($model->validatePassword($password)) {
//                    $model_info = \backend\models\Employee::find()->where(['id' => $model->employee_ref_id])->one();
//                    if ($model_info) {
//                        array_push($data, [
//                                'username' => $username,
//                                'user_id' => '' . $model->id,]
//                        );
//                    }
//
//                }
//
//            }
//        }
//
//        return ['status' => true, 'data' => $data];
//    }
}
