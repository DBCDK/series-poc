
<?php
/**
 * @file
 * Modified version of the default theme implementation for displaying a single
 * search result. The h3 title have been removed.
 *
 *
 * @see template_preprocess()
 * @see template_preprocess_search_result()
 * @see template_process()
 */
?>

<?php if ($items) : ?>
    <ul class="ting-series-menu">
      <?php foreach ($items as $item) : ?>
            <?php print $item; ?>
      <?php endforeach; ?>
    </ul>
<?php endif; ?>
