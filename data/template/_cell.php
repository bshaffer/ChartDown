<?php if ($chord instanceof ChartDown_Chart_ChordGroup): $group = $chord ?>
    <td style="width:<?php echo $percent ?>%" class="chord-group">
        <table style="width:100%">
            <tr>
        <?php $groupPercent = 100 * (1/count($group->getChords())) ?>
        <?php foreach ($group as $chord): ?>
            <?php echo $this->render('cell', array('percent' => $groupPercent, 'chord' => $chord, 'renderer' => $renderer)) ?>
        <?php endforeach ?>
            </tr>
        </table>
    </td>
<?php elseif ($chord instanceof ChartDown_Chart_Rhythm_RelativeMeterInterface): ?>
    <td style="width:<?php echo $percent * $chord->getRelativeMeter() ?>%">
        <span class="<?php echo $renderer->renderChartObjectClass($chord) ?>">
            <?php echo $renderer->renderChartObject($chord) ?>
        </span>
    </td>
<?php else: ?>
    <td class="<?php echo $renderer->renderChartObjectClass($chord) ?>"><span><?php echo $renderer->renderChartObject($chord) ?></span></td>
<?php endif ?>