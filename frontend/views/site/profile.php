<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = $user->username." Profile ";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
        <div class="col-lg-5">
<?php
echo "<img src='" . $user->avatar . "'<br><br>";

 echo "Username: ".$user->username."<br>";
 echo "Email: ".$user->email."<br>";
 if ($user->mobile)
 echo "Mobile Phone: ".$user->mobile."<br><br>";

 if ( Yii::$app->user->getId() == $user->id  )  {
echo Html::a("EDIT PROFILE", array('site/editprofile', 'id'=>$user->id));
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo Html::a("CANCEL ACCOUNT", array('site/deleteprofile', 'id'=>$user->id), array('onclick' => "return confirm('Are you sure you want to cancel account?')"));
        }

?>

                <div class="form-group">

                </div>


        </div>
    </div>

</div>
