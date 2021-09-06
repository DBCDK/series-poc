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
<div class="ting-series-main-content">
  <div class="ting-series-all-object-cover">
    <?php print $covers ?>
  </div>

  <div class="ting-series-all-object-content">
    <div class="ting-series-series-title">
      <h2><?php print $title ?></h2>
    </div>
    <div class="ting-series-series-abstract">
      <?php print $abstract ?>
    </div>
    <div class="ting-series-series-alt-title">
      <?php if ($alt_title) : ?>
        <?php print $alt_title ?>
      <?php endif; ?>
    </div>
    <div class="ting-series-series-universe">
      <?php if ($universe) : ?>
        <?php print $universe ?>
      <?php endif; ?>
    </div>

    <div class="ting-series-series-read-at-will">
      <?php if (isset($read_at_will)) : ?>
        <?php print $read_at_will ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if ($items) : ?>
  <ul class="ting-series-items">
    <?php foreach ($items as $item) : ?>
      <?php print $item; ?>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>