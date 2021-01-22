<?php

namespace backend\controllers;

use backend\models\StreamAssignSearch;
use Yii;
use backend\models\Cardaily;
use backend\models\CardailySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CardailyController implements the CRUD actions for Cardaily model.
 */
class CardailyController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Cardaily models.
     * @return mixed
     */
    public function actionIndex()
    {
       // print_r(Yii::$app->request->queryParams);return;
        $route_type_id = null;
        $car_name = null;
        if(isset(Yii::$app->request->queryParams['CardailySearch'])){
            $route_type_id =  Yii::$app->request->queryParams['CardailySearch']['route_id'];
        }
        if(isset(Yii::$app->request->queryParams['CardailySearch'])){
            $car_name =  Yii::$app->request->queryParams['CardailySearch']['car_name'];
        }

        $car_search_text = \Yii::$app->request->post('car_name');

        $searchModel = new CardailySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

      //  echo $route_type_id;return;
        $query = \common\models\QueryCarRoute::find();
        if($route_type_id != null){
            $query = $query->andFilterWhere(['delivery_route_id'=>$route_type_id]);
        }
        if($car_name != null){
            $query = $query->andFilterWhere(['OR',['LIKE','name',$car_name],['LIKE','code',$car_name]]);
        }
        $model_car = $query->all();

        return $this->render('_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model_car' => $model_car
        ]);
//        $searchModel = new CardailySearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       // $dataProvider->pagination->pageSize = 1000;
       // $model = \backend\models\Cardaily::find()->all();

       // print_r($model);return;

//        return $this->render('_index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Cardaily();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cardaily model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cardaily model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cardaily model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cardaily the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cardaily::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAddemp(){
        $t_date = null;
        $car_id = \Yii::$app->request->post('selected_car');
        $t_date = \Yii::$app->request->post('selected_date');
        $emp_id = \Yii::$app->request->post('line_car_emp_id');

        if($t_date == null){
            $t_date = date('Y-m-d');
        }
       // print_r($emp_id);return;
        if($car_id){
            if(count($emp_id) >0 ){
                for($i=0;$i<=count($emp_id)-1;$i++){
                    $this->checkOld($emp_id[$i], $car_id, $t_date);
                    $model = new \backend\models\Cardaily();
                    $model->car_id = $car_id;
                    $model->employee_id = $emp_id[$i];
                    $model->trans_date = $t_date;
                    $model->status = 1;
                    $model->save(false);
                }
            }
        }
        return $this->redirect(['index']);
    }
    public function checkOld($emp_id, $car_id, $t_date){
          $model = \backend\models\Cardaily::find()->where(['car_id'=>$car_id,'employee_id'=>$emp_id,'date(trans_date)'=>$t_date])->one();
          if($model){
              \backend\models\Cardaily::deleteAll(['car_id'=>$car_id,'employee_id'=>$emp_id,'date(trans_date)'=>$t_date]);
          }
    }
}