<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <legend>
        <?php if ($this->get('box') != ''): ?>
            <?=$this->getTrans('editBox') ?>
        <?php else: ?>
            <?=$this->getTrans('addBox') ?>
        <?php endif; ?>
    </legend>
    <div class="form-group">
        <label for="boxTitleInput" class="col-lg-2 control-label">
            <?=$this->getTrans('boxTitle') ?>:
        </label>
        <div class="col-lg-2">
            <input type="text"
                   class="form-control"
                   id="boxTitleInput"
                   name="boxTitle"
                   value="<?php if ($this->get('box') != '') { echo $this->escape($this->get('box')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <textarea class="form-control ckeditor"
                  id="ck_1"
                  name="boxContent"
                  toolbar="ilch_html"><?php if ($this->get('box') != '') { echo $this->get('box')->getContent(); } ?></textarea>
    </div>
    <?php if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != ''): ?>
        <div class="form-group">
            <label for="boxLanguageInput" class="col-lg-2 control-label">
                <?=$this->getTrans('boxLanguage') ?>:
            </label>
            <div class="col-lg-2">
                <select class="form-control" id="boxLanguageInput" name="boxLanguage">
                    <?php foreach ($this->get('languages') as $key => $value): ?>
                        <?php $selected = ''; ?>

                        <?php if ($key == $this->get('contentLanguage')): ?>
                            <?php continue; ?>
                        <?php endif; ?>

                        <?php if ($this->getRequest()->getParam('locale') == $key): ?>
                            <?php $selected = 'selected="selected"'; ?>
                        <?php endif; ?>

                        <option <?=$selected ?> value="<?=$key ?>"><?=$this->escape($value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->get('box') != ''): ?>
        <?=$this->getSaveBar('updateButtonBox') ?>
    <?php else: ?>
        <?=$this->getSaveBar('addButtonBox') ?>
    <?php endif; ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
<?php $boxID = ''; ?>
<?php if ($this->get('box') != ''): ?>
    <?php $boxID = $this->get('box')->getId(); ?>
<?php endif; ?>

$('#boxLanguageInput').change (
    this,
    function () {
        top.location.href = '<?=$this->getUrl(['id' => $boxID]); ?>/locale/'+$(this).val()
    }
);
</script>
