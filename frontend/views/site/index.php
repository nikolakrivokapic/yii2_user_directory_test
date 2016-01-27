<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'User Directory Test';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>User Directory Test</h1>

        <p class="lead">by Nikola Krivokapic</p>
       </div>
        <?php $form = ActiveForm::begin()  ?>
    <?=   $form->field($searchmodel, 'query')->textInput(['class' => 'col-xs-3'])->label(false) ?>     <br><br>

       <?=   Html::submitButton('Search Users', ['class' => 'btn btn-lg btn-success'])  ?>

      <?php  ActiveForm::end(); ?>




    <div class="body-content">

        <div class="row">

<?php
           foreach($users as $row)
        {  ?>
               <div class="col-lg-3">
                <h2><?php echo $row->username; ?></h2>
                <?php echo "<img src='" . $row->avatar . "' width=100 height=100><br><br>";    ?>
                <p><?php echo $row->email; ?></p>
             <?php  echo Html::a("SHOW PROFILE", array('site/publicprofile', 'userid'=>$row->id), array('class' => 'btn btn-default'));   ?>


            </div>
   <?php }   ?>
                <?php
               // display pagination

                echo LinkPager::widget([
                'pagination' => $pages,
                 ]);
                 ?>

        </div>

    </div>
</div>
