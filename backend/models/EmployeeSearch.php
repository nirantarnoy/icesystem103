<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `backend\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    public $globalSearch;
    public function rules()
    {
        return [
            [['id', 'gender', 'position', 'salary_type', 'status', 'company_id', 'branch_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['code', 'fname', 'lname', 'emp_start', 'description', 'photo'], 'safe'],
            [['globalSearch'],'string']
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
        $query = Employee::find();

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
            'gender' => $this->gender,
            'position' => $this->position,
            'salary_type' => $this->salary_type,
            'emp_start' => $this->emp_start,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'branch_id' => $this->branch_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        if(isset($_SESSION['user_company_id'])){
            $query->andFilterWhere(['company_id'=>$_SESSION['user_company_id']]);
        }
        if(isset($_SESSION['user_branch_id'])){
            $query->andFilterWhere(['branch_id'=>$_SESSION['user_branch_id']]);
        }

        if($this->globalSearch != ''){
            $query->orFilterWhere(['like', 'code', $this->globalSearch])
                ->orFilterWhere(['like', 'fname', $this->globalSearch])
                ->orFilterWhere(['like', 'lname', $this->globalSearch])
                ->orFilterWhere(['like', 'description', $this->globalSearch])
                ->orFilterWhere(['like', 'photo', $this->globalSearch]);
        }

        return $dataProvider;
    }
}
