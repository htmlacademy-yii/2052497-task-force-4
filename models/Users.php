<?php

namespace app\models;

use taskforce\business\Task;
use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $dt_add
 * @property string $name
 * @property string $email
 * @property string $dob
 * @property string $password
 * @property string $phonenumber
 * @property string|null $telegram
 * @property int|null $city_id
 * @property string|null $avatar
 * @property string|null $description
 * @property int $status
 * @property int $show_contacts
 * @property int|null $vk_id
 *
 * @property Categories[] $categories
 * @property Cities $city
 * @property ExecutorCategories[] $executorCategories
 * @property Offers[] $offers
 * @property Reviews[] $reviews
 * @property Reviews[] $reviews0
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 * @property Tasks[] $tasks1
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'name' => 'Name',
            'email' => 'Email',
            'dob' => 'Dob',
            'password' => 'Password',
            'phonenumber' => 'Phonenumber',
            'telegram' => 'Telegram',
            'city_id' => 'City ID',
            'avatar' => 'Avatar',
            'description' => 'Description',
            'status' => 'Status',
            'show_contacts' => 'Show Contacts',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])->viaTable('executor_categories', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[ExecutorCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorCategories()
    {
        return $this->hasMany(ExecutorCategories::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Offers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerReviews()
    {
        return $this->hasMany(Reviews::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutorReviews()
    {
        return $this->hasMany(Reviews::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Tasks::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks1()
    {
        return $this->hasMany(Tasks::class, ['id' => 'task_id'])->viaTable('offers', ['executor_id' => 'id']);
    }

    public function getAge()
    {
        $age = date_diff(date_create(date('Y-m-d')), date_create($this->dob));

        return $age->format("%y");
    }

    public function getCountDoneTasks()
    {
        return Tasks::find()->where(['executor_id' => $this->id, 'status' => Task::STATUS_DONE])->count();
    }
    public function getCountFailTasks()
    {
        return Tasks::find()->where(['executor_id' => $this->id, 'status' => Task::STATUS_FAIL])->count();
    }

    public function getPositionInRating()
    {
        $rank = Users::find()
        ->select('ROW_NUMBER() OVER (ORDER BY rating DESC) as number')
        ->indexBy('id')
        ->column();
        return $rank[$this->id];
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function updateRating()
    {
        $sum = Reviews::find()->where(['executor_id' => $this->id])->sum('rating');
        $countReview = Reviews::find()->where('rating > 0')->andFilterWhere(['executor_id' => $this->id])->count('rating');
        $countFail = Tasks::find()->where(['executor_id' => $this->id, 'status' => Task::STATUS_FAIL])->count('id');
        $rating = $sum / ($countFail + $countReview);
        $this->rating = round($rating, 2);
        $this->save();
    }
}
