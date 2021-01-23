<?php
namespace backend\models;
use Yii;
use yii\db\ActiveRecord;
date_default_timezone_set('Asia/Bangkok');

class Orders extends \common\models\Orders
{
    public function behaviors()
    {
        return [
            'timestampcdate'=>[
                'class'=> \yii\behaviors\AttributeBehavior::className(),
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_INSERT=>'created_at',
                ],
                'value'=> time(),
            ],
            'timestampudate'=>[
                'class'=> \yii\behaviors\AttributeBehavior::className(),
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_INSERT=>'updated_at',
                ],
                'value'=> time(),
            ],
//            'timestampcby'=>[
//                'class'=> \yii\behaviors\AttributeBehavior::className(),
//                'attributes'=>[
//                    ActiveRecord::EVENT_BEFORE_INSERT=>'created_by',
//                ],
//                'value'=> Yii::$app->user->identity->id,
//            ],
//            'timestamuby'=>[
//                'class'=> \yii\behaviors\AttributeBehavior::className(),
//                'attributes'=>[
//                    ActiveRecord::EVENT_BEFORE_UPDATE=>'updated_by',
//                ],
//                'value'=> Yii::$app->user->identity->id,
//            ],
            'timestampupdate'=>[
                'class'=> \yii\behaviors\AttributeBehavior::className(),
                'attributes'=>[
                    ActiveRecord::EVENT_BEFORE_UPDATE=>'updated_at',
                ],
                'value'=> time(),
            ],
        ];
    }
    public static function getLastNo(){
        $model = Orders::find()->MAX('order_no');
        //$pre = \backend\models\Sequence::find()->where(['module_id'=>15])->one();
        $pre = "SO";
        if($model){
            $prefix = $pre.substr(date("Y"),2,2);
            $cnum = substr((string)$model,4,strlen($model));
            $len = strlen($cnum);
            $clen = strlen($cnum + 1);
            $loop = $len - $clen;
            for($i=1;$i<=$loop;$i++){
                $prefix.="0";
            }
            $prefix.=$cnum + 1;
            return $prefix;
        }else{
            $prefix =$pre.substr(date("Y"),2,2);
            return $prefix.'000001';
        }
    }

    public function findOrderemp($id){
        $html = '';
        if ($id) {
//            $x_date = explode('/', $trans_date);
//            $t_date = date('Y-m-d');
//            if (count($x_date) > 1) {
//                $t_date = $x_date[2] . '/' . $x_date[1] . '/' . $x_date[0];
//            }

            $model_o = \backend\models\Orders::find()->where(['id'=>$id])->one();
            if($model_o){
                $t_date = date('Y-m-d', strtotime($model_o->order_date));

                $model = \backend\models\Cardaily::find()->where(['car_id' => $model_o->car_ref_id, 'date(trans_date)' => $t_date])->all();
                $i = 0;
                foreach ($model as $value) {
                    $i += 1;
                    $emp_code = \backend\models\Employee::findCode($value->employee_id);
                    $emp_fullname = \backend\models\Employee::findFullName($value->employee_id);

                    $html .= $emp_fullname . ' , ';

                }
            }

        }

        return $html;
    }

//    public function findUnitname($id){
//        $model = Unit::find()->where(['id'=>$id])->one();
//        return count($model)>0?$model->name:'';
//    }
//    public function findName($id){
//        $model = Unit::find()->where(['id'=>$id])->one();
//        return count($model)>0?$model->name:'';
//    }
//    public function findUnitid($code){
//        $model = Unit::find()->where(['name'=>$code])->one();
//        return count($model)>0?$model->id:0;
//    }

}
