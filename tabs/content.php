<?php
    $editorId = ($_GET['sub']) ? 'editor' : 'editor-readonly';
?>

<input type="text" placeholder="Search Related Content" class="form-control mb-2 search_content">

<div class="row">
<div class="col-md-12 searchContentDiv"></div>
</div>

<!-- <div id="<?php echo $editorId; ?>" style="height:400px; font-size: 14px;"> -->
<textarea name="editor1" id="editor1" rows="13" cols="80"><?php echo ($dataTask['taskContent']) ? $dataTask['taskContent']: "" ; ?></textarea>

<?php if ($_GET['sub']) : ?>
<input type="hidden" class="taskID" value="<?php echo $_GET['id']; ?>">
<button style="float:right;" type="button" class="btn btn-success btn-sm mt-2" id="saveContent">Save Content</button>
<?php endif; ?>