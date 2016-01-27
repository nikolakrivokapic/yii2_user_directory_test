<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Edit Profile';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('http://code.jquery.com/jquery-1.11.3.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
       Edit your profile and click Save to save changes.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'username')->textInput(['readonly' => true, 'value' => $user->username])  ?>

                <?= $form->field($model, 'email')->textInput(['value' => $user->email])  ?>

                <?= $form->field($model, 'mobile')->textInput(['value' => $user->mobile])  ?>

                 <?php echo "<img id='blah' src='" . $user->avatar . "'><br><br>";    ?>

               <?= $form->field($model, 'avatar')->fileInput(['onchange' => "readURL(this);"]) ?>



                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
<script>
     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

</script>