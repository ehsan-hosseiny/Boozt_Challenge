<?php
/** @var $model \App\Models\User  */
?>

<h1>Login</h1>

<?php $form =  \App\Core\form\Form::begin('','post') ?>
<?php echo $form->field($model,'email') ?>
<?php echo $form->field($model,'password')->passwordField() ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php echo \App\Core\form\Form::end() ?>