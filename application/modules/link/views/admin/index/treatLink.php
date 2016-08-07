<legend>
    <?php
    if ($this->get('link') != '') {
        echo $this->getTrans('menuActionEditLink');
    } else {
        echo $this->getTrans('menuActionNewLink');
    }
    ?>
</legend>

<?php if ($this->get('errors') !== null): ?>
    <div class="alert alert-danger" role="alert">
        <strong> <?=$this->getTrans('errorsOccured') ?>:</strong>
        <ul>
            <?php foreach ($this->get('errors') as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="name"
                   name="name"
                   placeholder="Name"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-lg-2 control-label">
            <?=$this->getTrans('link') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   id="link"
                   name="link"
                   placeholder="http://"
                   value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getLink()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="banner" class="col-lg-2 control-label">
            <?=$this->getTrans('banner') ?>:
        </label>
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text"
                       class="form-control"
                       id="selectedImage_1"
                       name="banner"
                       placeholder="<?=$this->getTrans('httpOrMedia') ?>"
                       value="<?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getBanner()); } ?>" />
                <span class="input-group-addon"><a id="media" href="javascript:media_1()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('description') ?>:
        </label>
        <div class="col-lg-4">
            <textarea class="form-control"
                      name="desc" 
                      cols="45" 
                      rows="3"><?php if ($this->get('link') != '') { echo $this->escape($this->get('link')->getDesc()); } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="desc" class="col-lg-2 control-label">
            <?=$this->getTrans('category'); ?>:
        </label>
        <div class="col-lg-4">
            <select class="form-control" name="catId">
                <option>-- <?=$this->getTrans('optionNoCategory')?> --</option>
                <?php
                if ($this->get('cats') != '') {
                    foreach ($this->get('cats') as $model) {
                        $selected = '';
                        if ($this->get('link') != '' && $this->get('link')->getCatId() == $model->getId()) {
                            $selected = 'selected="selected"';
                        }

                        echo '<option '.$selected.' value="'.$model->getId().'">'.$this->escape($model->getName()).'</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <?php
    if ($this->get('link') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
