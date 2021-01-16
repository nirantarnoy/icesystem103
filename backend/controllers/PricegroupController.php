<?php

namespace backend\controllers;

use backend\models\CustomerSearch;
use Yii;
use backend\models\Pricegroup;
use backend\models\PricegroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PricegroupController implements the CRUD actions for Pricegroup model.
 */
class PricegroupController extends Controller
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

    public function actionIndex()
    {
        $pageSize = 50;
        $pageSize = \Yii::$app->request->post("perpage");
        $searchModel = new PricegroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['id' => SORT_DESC]]);
        $dataProvider->pagination->pageSize = $pageSize;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'perpage' => $pageSize,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Pricegroup();

        if ($model->load(Yii::$app->request->post())) {
//            $prod_id = \Yii::$app->request->post('line_prod_id');
//            $prod_price = \Yii::$app->request->post('line_price');

            //print_r($prod_price);return;
            $model->status = 1;
            if ($model->save()) {
//                if (count($prod_id)) {
//                    for ($i = 0; $i <= count($prod_id) - 1; $i++) {
//                        $model_line = new \common\models\PriceGroupLine();
//                        $model_line->price_group_id = $model->id;
//                        $model_line->product_id = $prod_id[$i];
//                        $model_line->sale_price = $prod_price[$i] == null ? 0 : $prod_price[$i];
//                        $model_line->status = 1;
//                        $model_line->save();
//                    }
//                }
                $session = Yii::$app->session;
                $session->setFlash('msg', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['update', 'id' => $model->id]);
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_detail = \common\models\PriceGroupLine::find()->where(['price_group_id' => $id])->all();
        $model_customer_type = \common\models\PriceCustomerType::find()->where(['price_group_id' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {
            $recid = \Yii::$app->request->post('line_rec_id');
            $prod_id = \Yii::$app->request->post('line_prod_id');
            $prod_price = \Yii::$app->request->post('line_price');
            $removelist = \Yii::$app->request->post('removelist');

            $recid2 = \Yii::$app->request->post('line_rec_type_id');
            $customer_type_id = \Yii::$app->request->post('line_type_id');
            $removelist2 = \Yii::$app->request->post('removelist2');

           // print_r($removelist2);return;

            if ($model->save()) {
                if (count($prod_id) > 0) {
                    for ($i = 0; $i <= count($prod_id) - 1; $i++) {
                        if($prod_id[$i] == ''){continue;}
                        $model_update = \common\models\PriceGroupLine::find()->where(['product_id' => $prod_id[$i],'price_group_id'=>$model->id])->one();
                        if ($model_update) {
                            $model_update->sale_price = $prod_price[$i] == null ? 0 : $prod_price[$i];
                            $model_update->save(false);
                        } else {
                            $model_line = new \common\models\PriceGroupLine();
                            $model_line->price_group_id = $model->id;
                            $model_line->product_id = $prod_id[$i];
                            $model_line->sale_price = $prod_price[$i] == null ? 0 : $prod_price[$i];
                            $model_line->status = 1;
                            $model_line->save();
                        }
                    }
                }

                if (count($customer_type_id) > 0) {
                    for ($i = 0; $i <= count($customer_type_id) - 1; $i++) {
                        if($customer_type_id[$i] == ''){continue;}
                        $model_update = \common\models\PriceCustomerType::find()->where(['customer_type_id' => $customer_type_id[$i],'price_group_id'=>$model->id])->one();
                        if ($model_update) {

                        } else {
                            $model_line = new \common\models\PriceCustomerType();
                            $model_line->price_group_id = $model->id;
                            $model_line->customer_type_id = $customer_type_id[$i];
                            $model_line->status = 1;
                            $model_line->save();
                        }
                    }
                }

                if ($removelist != '') {
                    $x=explode(',',$removelist);
                    if(count($x) >0) {
                        for ($m = 0; $m <= count($x) - 1; $m++) {
                            \common\models\PriceGroupLine::deleteAll(['id' => $x[$m]]);
                        }
                    }

                }
                if ($removelist2 != '') {
                    $x=explode(',',$removelist2);
                    if(count($x) >0){
                        for($m=0;$m<=count($x)-1;$m++){
                            \common\models\PriceCustomerType::deleteAll(['id' => $x[$m]]);
                        }
                    }
                }

                $session = Yii::$app->session;
                $session->setFlash('msg', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_detail' => $model_detail,
            'model_customer_type' => $model_customer_type
        ]);
    }

    public function actionDelete($id)
    {
        \common\models\PriceGroupLine::deleteAll(['price_group_id' => $id]);

        $this->findModel($id)->delete();
        $session = Yii::$app->session;
        $session->setFlash('msg', 'ดำเนินการเรียบร้อย');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Pricegroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionProductdata()
    {
        $html = '';
        $model = \backend\models\Product::find()->all();
        foreach ($model as $value) {
            $html .= '<tr>';
            $html .= '<td style="text-align: center">
                        <div class="btn btn-outline-success btn-sm" onclick="addselecteditem($(this))" data-var="' . $value->id . '">เลือก</div>
                        <input type="hidden" class="line-find-code" value="' . $value->code . '">
                        <input type="hidden" class="line-find-name" value="' . $value->name . '">
                        <input type="hidden" class="line-find-price" value="' . $value->sale_price . '">
                       </td>';
            $html .= '<td>' . $value->code . '</td>';
            $html .= '<td>' . $value->name . '</td>';
            $html .= '<td>' . number_format($value->std_cost) . '</td>';
            $html .= '<td>' . number_format($value->sale_price) . '</td>';
            $html .= '</tr>';
        }
        echo $html;
    }

    public function actionCustomertypedata()
    {
        $html = '';
        $model = \backend\models\Customertype::find()->all();
        foreach ($model as $value) {
            $html .= '<tr>';
            $html .= '<td style="text-align: center">
                        <div class="btn btn-outline-success btn-sm" onclick="addselecteditem2($(this))" data-var="' . $value->id . '">เลือก</div>
                        <input type="hidden" class="line-find-type-code" value="' . $value->code . '">
                        <input type="hidden" class="line-find-type-name" value="' . $value->name . '">
                       </td>';
            $html .= '<td>' . $value->code . '</td>';
            $html .= '<td>' . $value->name . '</td>';
            $html .= '</tr>';
        }
        echo $html;
    }

}