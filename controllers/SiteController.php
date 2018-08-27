<?php

namespace app\controllers;

use app\models\Repo;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;


class SiteController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax && isset($_POST['action'])) {
            $this->enableCsrfValidation = false;
        }

        if (parent::beforeAction($action)) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            \app\behaviors\Metatags::class,
            \app\behaviors\MemTable::class,
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
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
     * {@inheritdoc}
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
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionApi()
    {
        if(!Yii::$app->request->isAjax) return;
        if(empty($_POST['action'])) return;
        $action = $_POST['action'];

        switch ($action){
            case 'ADD':
                if(Repo::addTask($_POST['data'])){
                    die("added");
                }else{
                    die("Error");
                }
            case 'LIST':
                die(json_encode(repo::listData($_POST['filter'])));
            case 'DELETE':
                if(Repo::deleteTask($_POST['uuid'])){
                    die("delete ok");
                }else{
                    die("Error on delete");
                }
            case 'UPDATE':
                if(Repo::update($_POST['uuid'], $_POST['field'], $_POST['newVal'])){
                    die("update ok");
                }else{
                    die("error");
                }
        }
    }
}
