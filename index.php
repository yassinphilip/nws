<?
/*
  index : View all feeds

  This script is part of NWS
  https://github.com/xaccrocheur/nws/

*/
?>

<!DOCTYPE html>
<html>
<head>
<title>nws</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" type="image/x-icon" href="img/nws.png" />
    <link href="libs/jquery-ui.css" rel="stylesheet" type="text/css" />
    <style type="text/css" media="screen">@import "nws-style.css";</style>
    <base target='_blank' />
</head>
<body>

<script src="libs/jquery.min.js"></script>
<script src="libs/jquery-ui.min.js"></script>

<script>

$(function() {
    $.ajaxSetup ({
        cache: true
    });

    var ajax_load = '<img src="img/loading.gif" class="loading" alt="loading..." />';
    var loadUrl = 'nws-load-feed.php';

//    var myOtherUrl = "http://example.com/index.html?url=" + encodeURIComponent(myUrl);

    $('.reload').click(function(){
        var DivToReload = $(this).parent()
        var myUrl = encodeURIComponent(DivToReload.attr('title'))
        DivToReload.children('div.innerContainer')
            .html(ajax_load)
            .load(loadUrl, "z="+myUrl);
    });

    $( "#tabs" ).tabs().find( ".ui-tabs-nav" ).sortable({ axis: "x" });
    $('.reload').trigger('click');

});

</script>

<div id="tabs">
<?php

$urls = simplexml_load_file('feeds.xml');


function outerContainer($u) {
    echo '
        <div class="outerContainer" style="" title ="'.$u.'">
            <span class="reload" title="Reload '.$u.'">&#9889;</span>
            <div class="innerContainer"></div>
        </div>
';
}

foreach ($urls->url as $url) {
    $myUrls[] = $url;
    foreach($url->attributes() as $attr => $val) {
        if ($attr == 'tab') {
            $myTabs[] = array('tab'=> (string) $val, 'url'=> (string) $url);
        }
    }
}

foreach($myTabs as $aRow){
    $tabGroups[$aRow['tab']][] = $aRow['url'];
}

echo '
    <ul>';

foreach (array_keys($tabGroups) as $tabName) {
    echo '
        <li><a title="'.$tabName.', Drag to re-order" href="#tab-'.$tabName.'"><span>'.$tabName.'</span></a></li>';
}

echo '
    </ul>';

foreach (array_keys($tabGroups) as $tabName) {
    echo '
    <div id="tab-'.$tabName.'">';
    foreach ($tabGroups[$tabName] as $tabUrl) {
        outerContainer($tabUrl);
    }
    echo '
    </div>';
}

?>

</div>
<a href="nws-manage.php">Manage feeds</a>
</body>
</html>
