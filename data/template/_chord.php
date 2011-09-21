<?php if ($chord instanceof ChartDown_Chart_ChordGroup): $group = $chord ?>
    <span style="width:<?php echo $percent ?>%" class="chord-group">
        <?php $groupPercent = 100 * (1/count($group->getChords())) ?>
        <?php foreach ($group as $chord): ?>
            <?php echo $this->render('chord', array('percent' => $groupPercent, 'chord' => $chord, 'renderer' => $renderer)) ?>
        <?php endforeach ?>
    </span>
<?php elseif ($chord instanceof ChartDown_Chart_Chord): ?>
    <span style="width:<?php echo $percent ?>%" class="chord<?php echo $renderer->renderChordExpressions($chord) ?>"><?php echo $chord ?></span>
<?php else: ?>
    <span style="width:<?php echo $percent ?>%" class="rhythm">&nbsp;</span>
<?php endif ?>