<?php
class MessageController extends CController
{
    
    public function actionCreate()
    {
        $model=new Message();
        if(isset($_POST['Message']))
        {
            $model->attributes=$_POST['Message'];
            if($model->save())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        $this->render('messaging.views.basicMessageComposer',array('model'=>$model));
    }
}
?>
