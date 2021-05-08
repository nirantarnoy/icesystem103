<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Adjustment;

/**
 * AdjustmentSearch represents the model behind the search form of `backend\models\Adjustment`.
 */
class AdjustmentSearch extends Adjustment
{
   public $globalSearch;
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'created_by'], 'integer'],
            [['journal_no', 'trans_date', 'description'], 'safe'],
            [['globalSearch'],'string'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Adjustment::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'trans_date' => $this->trans_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        if(isset($_SESSION['user_company_id'])){
            $query->andFilterWhere(['company_id'=>$_SESSION['user_company_id']]);
        }
        if(isset($_SESSION['user_branch_id'])){
            $query->andFilterWhere(['branch_id'=>$_SESSION['user_branch_id']]);
        }

        if($this->globalSearch != ''){
            $query->orFilterWhere(['like', 'journal_no', $this->globalSearch])
                ->orFilterWhere(['like', 'description', $this->globalSearch]);
        }

        return $dataProvider;
    }
}
