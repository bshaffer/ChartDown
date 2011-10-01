<tr class="text-row">
    <?php foreach ($row as $bar): ?>
        <?php if ($bar->hasText()): ?>
            <td colspan="<?php echo $renderer->getColspan($row, $bar, $maxBars) ?>" class="text">
                <?php echo $bar->renderText() ?>
            </td>
        <?php endif ?>
    <?php endforeach ?>
</tr>
