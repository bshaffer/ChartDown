<?php if ($row->hasText($position)): ?>
    <tr class="text-row">
        <?php foreach ($row as $bar): ?>
            <?php if ($bar->hasText($position)): ?>
                <td colspan="<?php echo $renderer->getColspan($row, $bar, $position, $maxBars) ?>" class="text">
                    <?php echo $bar->renderText($position) ?>
                </td>
            <?php endif ?>
        <?php endforeach ?>
    </tr>
<?php endif ?>          
