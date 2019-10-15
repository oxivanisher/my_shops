<?php
defined('C5_EXECUTE') or die('Access Denied.');

/* @var Concrete\Core\Html\Service\Html $html */
/* @var Concrete\Core\Application\Service\UserInterface $interface */
/* @var Concrete\Core\Application\Service\Dashboard $dashboard */
/* @var Concrete\Core\Validation\CSRF\Token $token */
/* @var Concrete\Core\Form\Service\Form $form */
/* @var Concrete\Core\Page\View\PageView $view */
/* @var Concrete\Core\Page\Page $c */
/* @var Concrete\Theme\Dashboard\PageTheme $theme */

/* @var Concrete\Package\MyShops\Controller\SinglePage\Dashboard\Shops\Details $controller */

/* @var MyShops\Entity\Shop $shop */

$id = $shop->getId() ?: 'new';
?>

<form method="post" action="<?= $view->action('save', $id) ?>">
    <?php $token->output('myshops-shops-details-' . $id)?>
    <div class="form-group">
        <?= $form->label('name', t('Name')) ?>
        <div class="input-group">
            <?= $form->text('name', $shop->getName(), ['maxlength' => '255', 'required' => 'required']) ?>
            <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
        </div>
    </div>
    <div class="form-group">
        <?= $form->label('enabled', tc('Shop', 'Enabled/active')) ?>
        <div class="checkbox">
            <label><?= $form->radio('enabled', '1', $shop->isEnabled() ? '1' : '0', ['required' => 'required']) ?> <?= tc('Shop', 'Enabled (published)') ?></label>
        </div>
        <div class="checkbox">
            <label><?= $form->radio('enabled', '0', $shop->isEnabled() ? '1' : '0', ['required' => 'required']) ?> <?= tc('Shop', 'Disabled (not published)') ?></label>
        </div>
    </div>
    <div class="form-group">
        <?= $form->label('location', t('location')) ?>
        <?= $form->text('location', $shop->getLocation(), ['maxlength' => '255', 'required' => 'required']) ?>
    </div>
    <div class="form-group">
        <?= $form->label('products', t('products')) ?>
        <?= $form->text('products', $shop->getProducts(), ['maxlength' => '255', 'required' => 'required']) ?>
    </div>
    <div class="form-group">
        <?= $form->label('comment', t('comment')) ?>
        <?= $form->text('comment', $shop->getComment(), ['maxlength' => '255']) ?>
    </div>
    <div class="form-group">
        <?= $form->label('url', t('url')) ?>
        <?= $form->text('url', $shop->getUrl(), ['maxlength' => '255', 'required' => 'required']) ?>
    </div>
    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
	       <a href="<?= URL::to('/dashboard/shops') ?>" class="btn btn-default"><?= t('Cancel') ?></a>
            <div class="pull-right">
                <?php
                if ($id !== 'new') {
                    ?>
                    <a class="btn btn-danger" data-launch-dialog="myshops-shops-details-delete-dialog"><?= t('Delete') ?></a>
                    <?php
                }
                ?>
                <input type="submit" class="btn btn-primary" value="<?= $id === 'new' ? t('Create') : t('Save') ?>" />
            </div>
        </div>
    </div>
</form>
<?php
if ($id !== 'new') {
    ?>
    <div style="display: none" data-dialog="myshops-shops-details-delete-dialog" class="ccm-ui">
        <form data-dialog-form="myshops-shops-details-delete-form" method="POST" action="<?= $view->action('delete', $id) ?>">
            <?php $token->output('myshops-shops-details-delete-' . $id) ?>
            <p><?= t('Are you sure you want to permanently delete this shop?') ?></p>
            <p><strong><?= t('WARNING: this operation can not be undone!') ?></strong></p>
        </form>
        <div class="dialog-buttons">
            <button class="btn btn-default pull-left" data-dialog-action="cancel"><?= t('Cancel') ?></button>
            <button class="btn btn-danger pull-right" data-dialog-action="submit"><?= t('Delete') ?></button>
        </div>
    </div>
    <script>
    $(function() {
        var $dialog = $('div[data-dialog="myshops-shops-details-delete-dialog"]');
        $('[data-launch-dialog="myshops-shops-details-delete-dialog"]').on('click', function(e) {
            e.preventDefault();
            jQuery.fn.dialog.open({
                element: $dialog,
                modal: true,
                width: 420,
                title: <?= json_encode(t('Removal confirmation')) ?>,
                height: 'auto'
            });
        });
        ConcreteEvent.subscribe('AjaxFormSubmitSuccess', function(e, data) {
            if (data.form === 'myshops-shops-details-delete-form') {
                window.location.href = <?= json_encode((string) URL::to('/dashboard/shops')) ?>;
            }
        });
    });
    </script>
    <?php
}
