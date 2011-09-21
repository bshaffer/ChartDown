<?php if ($chord instanceof ChartDown_Chart_ChordGroup): $group = $chord ?>
    <td style="width:<?php echo $percent ?>%" class="chord-group">
        <table>
            <tr>
        <?php $groupPercent = 100 * (1/count($group->getChords())) ?>
        <?php foreach ($group as $chord): ?>
            <?php echo $this->render('cell', array('percent' => $groupPercent, 'chord' => $chord, 'renderer' => $renderer)) ?>
        <?php endforeach ?>
            </tr>
        </table>
    </td>
<?php elseif ($chord instanceof ChartDown_Chart_Chord): ?>
    <td style="width:<?php echo $percent ?>%" class="chord<?php echo $renderer->renderChordExpressions($chord) ?>"><?php echo $chord ?></td>
<?php else: ?>
    <td style="width:<?php echo $percent ?>%" class="rhythm">&nbsp;</td>
<?php endif ?>