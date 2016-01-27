<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\SearchForm;
use common\models\User;
use frontend\models\ContactForm;
use frontend\models\ProfileForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\SimpleImage;
use yii\data\Pagination;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

     $searchmodel = new SearchForm();

      if ($searchmodel->load(Yii::$app->request->post())) {        // in case of search
                 // matching by username or email
                 $query = User::find()
                 ->where([
                 'or',
                   ['like', 'username', $_POST['SearchForm']['query']],
                  ['like', 'email', $_POST['SearchForm']['query']],
                  ]);
           
                $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize'=>8]);
                $users = $query->offset($pagination->offset)
                ->limit(8)
                 ->all();

        return $this->render('index', [
                'users' => $users, 'pages' => $pagination, 'searchmodel' => $searchmodel
            ]);

          }

     $query = User::find();
     $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize'=>8]);
     $users = $query->offset($pagination->offset)
    ->limit(8)
    ->all();

        return $this->render('index', [
                'users' => $users, 'pages' => $pagination, 'searchmodel' => $searchmodel
            ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }


        public function actionProfile()
    {

       $userid =  Yii::$app->user->getId();
      $user = User::find()->where(['id' => $userid])->one();
       if($user ){
    	echo $user->username;

}

 return $this->render('profile', [
                'user' => $user,
            ]);


    }


           public function actionEditprofile()
    {

     $model = new ProfileForm();
     $userid =  Yii::$app->user->getId();
      $user = User::find()->where(['id' => $userid])->one();

      if ($model->load(Yii::$app->request->post())) {
          $user->username = $_POST['ProfileForm']['username'];
          $user->email = $_POST['ProfileForm']['email'];
          $user->mobile = $_POST['ProfileForm']['mobile'];

          $model->avatar = UploadedFile::getInstance($model, 'avatar');

            if ($model->avatar) {
                if($user->avatar) {      // if present delete old image
                $image_name= substr($user->avatar, strrpos($user->avatar, '/') + 1);
                unlink(Yii::$app->basePath.'/web/uploads/'. $image_name); // delete image
                }
                $model->avatar->saveAs('uploads/' . $model->avatar->baseName . '.' . $model->avatar->extension); // save Image in uploads folder
                $user->avatar = Yii::$app->getUrlManager()->getBaseUrl() . '/uploads/' . $model->avatar->baseName . '.' . $model->avatar->extension; // prepare image url for storing in DB
                  $imagePath = Yii::$app->basePath . '/web/uploads/' . $model->avatar->baseName . '.' . $model->avatar->extension; // image path for Image croping class SimpleImage
                  $img = new SimpleImage($imagePath);
                  $img->resize(100,100)->save();     //resize image to 100x100 for avatar

                 }
           if ($user->save())
            Yii::$app->response->redirect(array('site/profile', 'user' => $user));


      } else {
      return $this->render('edit_profile', [
                'model' => $model, 'user' => $user
            ]);
       }


    }


      public function actionDeleteprofile()
    {
      $userid =  Yii::$app->user->getId();
      $user = User::find()->where(['id' => $userid])->one();
      $user->delete();

    Yii::$app->user->logout();

        return $this->goHome();



    }



          public function actionPublicprofile($userid)
    {


      $user = User::find()->where(['id' => $userid])->one();
       if($user ){
    	echo $user->username;

    }

             return $this->render('profile', [
                'user' => $user,
            ]);


    }



    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
