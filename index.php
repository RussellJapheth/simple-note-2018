<?php
require __DIR__.'/vendor/autoload.php';

Flight::route('/', function () {
    Flight::render('index.php', []);
});

Flight::route('/view/(@note)', function ($note) {
    $notes = new Notes();
    $note = $notes->read($note);
    unset($notes);
    $note['title'] = htmlentities(ucwords($note['title']));
    if (!empty($note['tags'])) {
        $nts = $note['tags'];
        $note['tags'] = '';
        foreach ($nts as $nt) {
            $note['tags'] .= '<a onclick="return false;" href="/tags/'.htmlentities($nt).'" class="btn btn-xs btn-info">'.htmlentities($nt).'</a>';
        }
        unset($nts);
    } else {
        $note['tags'] = '';
    }
    $parsedown = new Parsedown;
    $parsedown->setSafeMode(true);
    $note['contents'] = $parsedown->text($note['contents']);
    $note['owner'] = htmlentities(ucwords($note['owner']));
    $note['ctime'] = date(DATE_RFC2822, $note['ctime']);
    $note['owner_url'] = urlencode($note['owner']);
    $html =<<<EOD
	<script>
		function delete_note(href){
		 if (window.confirm("Do you really want to delete this note?")) { 
				window.location = href;
			 	alert('Alright :)');
			}
		}
	</script>
    <link rel="stylesheet" href="/assets/css/default.min.css">
    <link rel="stylesheet" href="/assets/css/monokai-sublime.css">


	
<h4 class="text-center">$note[title] <small class="pull-right"><div class="btn-group"><a href="/delete/$note[id]" onclick="delete_note(this.href); return false;"; class="btn btn-danger fa fa-trash"></a> <!-- <a href="/edit/$note[id]" class="btn btn-info fa fa-pencil"></a> --> </div></small><div class="clearfix"></div></h4>
<text>$note[contents]</text>
<hr />
$note[tags]<small class="pull-right">created by: <a onclick="return false;" href="/authors/$note[owner_url]">$note[owner]</a> @ $note[ctime]</small>
<div class="clearfix"></div>		
    <script src="/assets/js/highlight.min.js"></script>
    <script>

$('pre code').each(function(i, block) {
  hljs.highlightBlock(block);
});
</script>
EOD;
    echo $html;
// echo "<pre>";
// var_dump($note);
// echo "</pre>";
});

Flight::route('/delete/(@note)', function ($note) {
    $notes = new Notes();
    $notes->delete($note);
    $notes->save();
    Flight::redirect('/');
});

Flight::route('/add', function () {
    $notes = new Notes();
    $notes->create($_POST);
    if ($notes->save()) {
        echo '<div class="alert alert-success"><strong><i class="fa fa-tick"></i> Note saved :)</strong></div><script>location.reload(true);</script>';
    } else {
        echo '<div class="alert alert-danger"><strong><i class="fa fa-times"></i> Note not saved :(</strong></div><script>location.reload(true);</script>';
    }
});

Flight::start();
