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
    <td style="width:<?php echo $percent ?>%">
        <span class="<?php echo $renderer->renderChartObjectClass($chord) ?>" <?php echo $renderer->renderChartObjectAttributes($chord) ?>>
            <?php if ($chord instanceof ChartDown_Chart_Chord): ?>
                <?php if ($chord->getRootNote()): ?>
                    <?php echo $chord->getRoot().$chord->getIntervalText() ?><?php if ($rest = $chord->getRest()): ?><span class="chord-extensions"><?php echo $chord->getRest() ?></span><?php endif ?><?php echo $chord->getBass() ? sprintf('/%s', $chord->getBass()) : '' ?>
                <?php else: ?>
                    <?php echo (string) $chord ?>
                <?php endif ?>
            <?php endif ?>
        </span>
    </td>
<?php else: ?>
    <td class="<?php echo $renderer->renderChartObjectClass($chord) ?>" <?php echo $renderer->renderChartObjectAttributes($chord) ?>>
        <span>&nbsp;</span>
    </td>
<?php endif ?>