<?php
class BasicMessageComposer extends CWidget
{    
    private $message;
    
    public function init()
    {
        $this->message=new Message();
    }
 
    public function run()
    {
        $this->render('messaging.views.basicMessageComposer', array('model'=>$this->message));
    }
}
?>
