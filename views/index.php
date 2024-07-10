<?php
$notes = new Notes();
// $notes->create();
// $notes->save();
$notes = $notes->read();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SimpleNote - Desktop Version</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="/assets/css/font-awesome.css" media="screen">
    <link rel="stylesheet" href="/assets/css/style.css" media="screen">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    
</head>
<body>
    <div class="container" id="main">
        <div class="row">
            <div class="col-xs-12">
            <div id="loading" class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> loading...</div>
                <div id="error"></div>
            </div>
            <div class="col-xs-4" id="menu">
                <div class="panel panel-success">
                  <!-- Default panel contents -->
                  <style>.panel-heading{font-size: 25px;text-align: center !important;font-weight: bolder !important;}</style>
                  <div class="panel-heading"><a href="" style="text-decoration: none;" class="text-success"><i class="fa fa-book"></i>Notes <i class="fa fa-laptop"></i></a></div>
                    <ul class="list-group">
                        <?php if (!empty($notes)) : ?>
                            <?php foreach ($notes as $id => $note) : ?>
                                <?php
                                $note['tags'] = implode(' ', $note['tags']);
                                    ?>
                                <li class="list-group-item" data-tags="<?= $note['tags']; ?>" onclick="load_note('<?= htmlentities($id); ?>');"><a href="#/view/<?= htmlentities($id); ?>"><?= htmlentities($note['title']); ?></a><br /></li>
                            <?php endforeach ?>
                        <?php else : ?>
                            <div class="alert alert-warning"><strong>No notes to display!</strong></div>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
            <div class="col-xs-8" id="stage">
                <?php if (!empty($notes)) : ?>
                    <div style="min-height: 300px;">
                        <center style="padding-top: 120px;"><i class="fa fa-book fa-5x"></i></center>
                    </div>
                <?php else : ?>
                    <div class="alert alert-warning"><strong>No notes to display!</strong></div>
                <?php endif ?>
            </div>
        </div>
        <div class="btn-group">
            <a class="btn btn-success fa fa-plus" id="add_note_button" href="#/add"> Add note</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <script>
        function hide_loading(speed = 'fast') {
            $('#loading').hide(speed);
        }
        function show_loading(speed = 'fast') {
            $('#loading').show(speed);
        }
        function add_error(msg) {
            $('#error').html('<div class="alert alert-danger" onclick="this.style.display =\'none\'"><strong>'+msg+'</strong></div>');
        }
        function load_note(id) {
            show_loading('slow');
            $('#stage').load('/view/'+id, {}, (response, status, xhr) => {
                if ( status == "error") {
                var msg = "Sorry but there was an error: ";
                add_error( msg + xhr.status + " " + xhr.statusText );
              }
                hide_loading('slow');
            });
        }
        $(document).ready(() => {
            hide_loading('fast');
            $('#add_note_button').click(() => {
                $('#add_note_modal').modal('show');
            });
            $('#save_note').click(() => {
                show_loading('slow');
                let post = {
                    title: $('#note_title').val(), 
                    owner: $('#note_owner').val(), 
                    tags: $('#note_tags').val(), 
                    contents: $('#note_contents').val()
                };
                $('#stage').load('/add', post, (response, status, xhr) => {
                        if ( status == "error") {
                        var msg = "Sorry but there was an error: ";
                        add_error( msg + xhr.status + " " + xhr.statusText );
                      }
                    hide_loading();
                    $('#reset_new_note_form').click();
            });
            });
        });
    </script>
    <script src="/assets/js/bootstrap.min.js"></script>
<!-- Modal -->
<div class="modal fade" id="add_note_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> New note</h4>
      </div>
      <div class="modal-body">
        <form id="add_new_note_form">
            <div class="form-group">
                <label for="note_title">Title</label>
                <input type="text" placeholder="enter a title here" class="form-control" name="note_title" id="note_title"/>
            </div>
            <div class="form-group">
                <label for="note_owner">Owner</label>
                <input type="text" class="form-control" placeholder="migo hogwort" name="note_owner" id="note_owner"/>
            </div>
            <div class="form-group">
                <label for="note_tags">Tags</label>
                <input type="text" class="form-control" placeholder="sample,note" name="note_tags" id="note_tags"/>
            </div>

            <div class="form-group">
                <label for="note_contents">Contents</label>
                <textarea rows="7" class="form-control" placeholder="Your note goes here! <?= "\r\n"; ?>Markdown is supported :)" name="note_contents" id="note_contents"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <a href="#/" onclick="window.location='#/';" class="btn btn-warning" id="reset_new_note_form" data-dismiss="modal"><i class="fa fa-trash"></i> Close</a>
        <button type="button" id="save_note" class="btn btn-success"><i class="fa fa-save"></i> Save note</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>
