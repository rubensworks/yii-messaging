<?php echo CHtml::link("LINK", array('messaging/message/create')) ?>>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'basic-message-composer-form',
    'action' => Yii::app()->createUrl('messaging/message/create'),
)); ?>
 
    <?php echo $form->errorSummary($model); ?>
 
    <div class="row">
        <?php echo $form->label($model,'grp'); ?>
        <?php echo $form->textField($model,'grp') ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'content'); ?>
        <?php echo $form->textArea($model,'content') ?>
    </div>

    </div>
 
    <div class="row submit">
        <?php echo CHtml::submitButton('Send'); ?>
    </div>
 
<?php $this->endWidget(); ?>
</div>