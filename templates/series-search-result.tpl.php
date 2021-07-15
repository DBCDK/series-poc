
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

<?php if ($results) : ?>
  <div class="search-results">
    <ul class="ting-object-tree">
      <?php foreach ($results as $result) : ?>
        <li class="ting-object-tree-item" style="display:inline-block">
          <?php if ($result): ?>
            <?php print $result; ?>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php else : ?>
  <div class="search-results">
    <h2><?php print t('Your search yielded no results'); ?></h2>
    <?php print search_help('search#noresults', drupal_help_arg()); ?>
  </div>
<?php endif; ?>
