<?php
$calendar = $this->get('calendar');

$start = new \Ilch\Date($calendar->getStart());
$end = new \Ilch\Date($calendar->getEnd());
?>

<legend><?=$this->escape($calendar->getTitle()) ?></legend>
<div class="form-horizontal">
    <?php if ($calendar->getPlace()!= ''): ?>
        <div class="form-group">
            <div class="col-lg-2"><?=$this->getTrans('place') ?></div>
            <div class="col-lg-10"><?=$this->escape($calendar->getPlace()) ?></div>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-lg-2"><?=$this->getTrans('start') ?></div>
        <div class="col-lg-10"><?=$start->format("l, d. F Y") ?> <?=$this->getTrans('at') ?> <?=$start->format("H:i") ?> <?=$this->getTrans('clock') ?></div>
    </div>
    <?php if ($calendar->getEnd()!= '0000-00-00 00:00:00'): ?>
    <div class="form-group">
        <div class="col-lg-2"><?=$this->getTrans('end') ?></div>
        <div class="col-lg-10"><?=$end->format("l, d. F Y") ?> <?=$this->getTrans('at') ?> <?=$end->format("H:i") ?> <?=$this->getTrans('clock') ?></div>
    </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-lg-2"><?=$this->getTrans('description') ?></div>
        <div class="col-lg-12">
            <?php if ($calendar->getText()!= ''): ?>
                <?=$calendar->getText() ?>
            <?php else: ?>
                <?=$this->getTrans('noDescription') ?>
            <?php endif; ?>
        </div>
    </div>
</div>
