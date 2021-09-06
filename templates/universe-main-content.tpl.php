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
  <div class="ting-series-all-object-cover-universe">
    <?php print $universe->covers ?>
  </div>

  <div class="ting-series-all-object-content">
    <div class="ting-series-series-title">
      <h2><?php print $universe->title ?></h2>
    </div>
    <div class="ting-series-series-abstract">
      <?php print $universe->abstract ?>
    </div>
    <div class="ting-series-series-alt-title">
      <?php if ($universe->alt_title) : ?>
        <?php print $universe->alt_title ?>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php if ($universe->items) : ?>
  <ul class="ting-series-items">
    <?php foreach ($universe->items as $item) : ?>
      <?php print $item; ?>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<?php if (!empty($universe->other_language_items)) : ?>
  <h2>Serier på andre sprog</h2>
  <ul class="ting-series-items">
    <?php foreach ($universe->other_language_items as $item1) : ?>
      <?php print $item1; ?>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<div class="ting-series-single-works">
  <?php if ($universe->single_works) : ?>
    <?php print $universe->single_works ?>
  <?php endif; ?>
</div>