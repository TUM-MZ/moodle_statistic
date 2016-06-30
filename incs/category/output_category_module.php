<?php
$padding_base = 15;
?>
<div class="category-output" data-style="module" draggable="true" ondragover="highlightDropArea(event);" ondrop="drop(event);" ondragstart="drag(event);">
    <?php
    include_once 'cat_selection.php';
    
    include_once "chart_options.php";

    include_once 'cat_summary.php';

    include_once 'module_details.php';
    ?>
</div>
