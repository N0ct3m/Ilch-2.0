<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<?php if ($this->get('inbox') != ''): ?>
    <?php $inbox = $this->get('inbox') ?>
    <div class="panel-body" id="boxscroll">
        <ul class="chat">
            <?php foreach ($inbox as $key): ?>
                <li class="left clearfix">
                    <span class="chat-img pull-left">
                        <img class="avatar" src="<?=$this->getUrl().'/'.$key->getAvatar() ?>" alt="User Avatar" class="img-circle">
                    </span>
                    <div class="chat-body clearfix">
                        <div class="header">
                            <strong class="primary-font"><?=$this->escape($key->getName()) ?></strong>
                            <small class="pull-right text-muted">
                                <i class="fa fa-clock-o"></i> <?=$key->getTime() ?>
                            </small>
                        </div>
                        <p><?=nl2br($this->getHtmlFromBBCode($this->escape($key->getText()))) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
