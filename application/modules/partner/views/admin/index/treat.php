<form class="form-horizontal" method="POST" action="<?=$this->getUrl(['action' => $this->getRequest()->getActionName(), 'id' => $this->getRequest()->getParam('id')]) ?>">
    <?=$this->getTokenField() ?>
    <legend>
        <?php
        if ($this->get('partner') != '') {
            echo $this->getTrans('edit');
        } else {
            echo $this->getTrans('add');
        }
        ?>
    </legend>
    <div class="form-group">
        <label for="name" class="col-lg-2 control-label">
            <?=$this->getTrans('name') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   name="name"
                   value="<?php if ($this->get('partner') != '') { echo $this->escape($this->get('partner')->getName()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-lg-2 control-label">
            <?=$this->getTrans('link') ?>:
        </label>
        <div class="col-lg-4">
            <input type="text"
                   class="form-control"
                   name="link"
                   placeholder="http://"
                   value="<?php if ($this->get('partner') != '') { echo $this->escape($this->get('partner')->getLink()); } ?>" />
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
                       value="<?php if ($this->get('partner') != '') { echo $this->escape($this->get('partner')->getBanner()); } ?>" />
                <span class="input-group-addon"><a id="media" href="javascript:media_1()"><i class="fa fa-picture-o"></i></a></span>
            </div>
        </div>
    </div>
    <?php
    if ($this->get('partner') != '') {
        echo $this->getSaveBar('updateButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>

<?=$this->getDialog('mediaModal', $this->getTrans('media'), '<iframe frameborder="0"></iframe>'); ?>
<script>
// Example for multiple input filds
<?=$this->getMedia()
        ->addMediaButton($this->getUrl('admin/media/iframe/index/type/single/input/_1/'))
        ->addInputId('_1')
        ->addUploadController($this->getUrl('admin/media/index/upload'))
?>
</script>
