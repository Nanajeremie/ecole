<?php
    include('../../../utilities/QueryBuilder.php');
    $title = 'Test';
    $breadcrumb = 'Test Preview';
    include('header.php');
    extract($_GET);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <a class="media" href="<?='../../Devoirs/'.$title?>"></a>
        </div>
    </div>
</div>

<?php
    include('../footer.php');
?>

<script>
(function ($) {
 "use strict";

		$('a.media').media({width:1260, height:490});
		 
 
})(jQuery); 
</script>