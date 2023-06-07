<?php

namespace backend\models;


use common\models\QueryPlan;
use yii\base\BaseObject;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\Session;

class PlansummarySearch extends QueryPlan
{
    public $globalSearch, $from_date, $to_date;


    public function rules()
    {
        return [
            [['company_id', 'branch_id',], 'integer'],
            [['trans_date','from_date','to_date'], 'safe'],
            [['globalSearch','code','name'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = QueryPlan::find()->select(['code','name','SUM(qty) as qty']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


//        if (!empty(\Yii::$app->user->identity->company_id)) {
//            $query->andFilterWhere(['plan.company_id' => \Yii::$app->user->identity->company_id]);
//        }
//        if (!empty(\Yii::$app->user->identity->branch_id)) {
//            $query->andFilterWhere(['plan.branch_id' => \Yii::$app->user->identity->branch_id]);
//        }


//        $sale_date = null;
//        if ($this->trans_date != null) {
//            $x_date = explode('/', $this->trans_date);
//            $sale_date = date('Y-m-d');
//            if (count($x_date) > 1) {
//                $sale_date = $x_date[2] . '/' . $x_date[1] . '/' . $x_date[0];
//            }
//            $this->trans_date = date('Y-m-d', strtotime($sale_date));
//            $query->andFilterWhere(['date(trans_date)' => date('Y-m-d', strtotime($sale_date))]);
//        }else{
//            $this->trans_date = date('Y-m-d');
//            $query->andFilterWhere(['date(trans_date)' => date('Y-m-d')]);
//        }
        if($this->from_date != null && $this->to_date != null){

            $f_date = null;
            $f_time = null;
            $t_date = null;
            $t_time = null;

            $from_date_time = null;
            $to_date_time = null;

            if($this->from_date != null){
                $f_date = $this->from_date;
                // $f_time = $fx_datetime[1];

                $x_date = explode('-', $f_date);
                $xx_date = date('Y-m-d');
                if (count($x_date) > 1) {
                    $xx_date = trim($x_date[1]) . '/' . trim($x_date[2]) . '/' . trim($x_date[0]);
                }
                //$from_date_time = date('Y-m-d H:i:s',strtotime($xx_date.' '.$f_time));
                $from_date_time = date('Y-m-d',strtotime($xx_date));
                $query->andFilterWhere(['>=','date(trans_date)', $from_date_time]);
            }

            if($this->to_date != null){
                $t_date = $this->to_date;
                // $t_time = $tx_datetime[1];

                $n_date = explode('-', $t_date);
                $nn_date = date('Y-m-d');
                if (count($n_date) > 1) {
                    $nn_date = trim($n_date[1]) . '/' . trim($n_date[2]) . '/' . trim($n_date[0]);
                }
                // $to_date_time = date('Y-m-d H:i:s',strtotime($nn_date.' '.$t_time));
                $to_date_time = date('Y-m-d',strtotime($nn_date));
                $query->andFilterWhere(['<=','date(trans_date)',$to_date_time]);
            }

        }

        if ($this->globalSearch != '') {
            $query
                ->orFilterWhere(['like', 'code', $this->globalSearch])
                ->orFilterWhere(['like', 'name', $this->globalSearch]);
        }


        return $dataProvider;
    }
}
