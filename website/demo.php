<?php

$page = 'Demo';
include_once 'header.php';

?>
<script src="/wikibits.js" type="text/javascript"></script>
<script src="https://bits.wikimedia.org/en.wikipedia.org/load.php?debug=false&lang=en&modules=startup&only=scripts&skin=vector&*"></script>
<script type="text/javascript">
// Mock Wikipedia environment ProveIt expects to run in

mw.config.set({
    "wgServer": "http://en.wikipedia.org",
    "wgScriptPath": "/w",
    "wgCanonicalNamespace": "",
    "wgCanonicalSpecialPageName": false,
    "wgDefaultDateFormat": "dmy",
    "wgMonthNames": ["","January","February","March","April","May","June","July","August","September","October","November","December"],
    "wgNamespaceNumber": 0,
    "wgAction": "edit"
});
</script>
<script type="text/javascript">
//<![CDATA[

function loadArticle(articleName)
{
    if(articleName == null)
        articleName = $('#articleName').val();
    else
        $('#articleName').val(articleName);

    $('#articleLink').text(articleName).attr('href', wgServer + '/wiki/' + encodeURIComponent(articleName));

    $('#articleName').attr('readonly','readonly');
    $('#articleBtn').attr('disabled','disabled');

    var apiURL = 'http://en.wikipedia.org/w/api.php?action=query&prop=revisions&titles=' + encodeURIComponent(articleName) + '&rvprop=content&format=json&callback=?';
    $.getJSON(apiURL, function(response)
    {
        if(response.error)
        {
            throw response.error;
        }
        else
        {
            $('#articleName').removeAttr('readonly');
            $('#articleBtn').removeAttr('disabled');
        }
        var pages = response.query.pages;
        for(var key in pages)
        {
            break; // Get first (only) key.  This is necessary due to odd JSON structure.
        }
        var page = pages[key];
        var content = page.revisions[0]['*'];
        // wg's global
        wgTitle = page.title;
        wgPageName = page.title.replace(" ", "_");
        $('#wpTextbox1').val(content);
        $('#proveit').remove();
        proveit.createGUI();
        proveit.toggleViewAddVisibility();
    });
    return false;
}

function log()
{
    if(typeof(console) === 'object' && console.log)
    {
        console.log.apply(null, arguments);
    }
}

mw.loader.using("ext.gadget.ProveIt", function()
{
    $(function()
    {
        $('#demoForm').submit(function(evt){
            loadArticle();
            evt.preventDefault();
        });
        loadArticle();
    });
}, function(ex, errorDependencies)
{
    log('Failed to load ProveIt due to missing dependencies: ', errorDependencies);
});

//]]>
</script>
				<table id="mainTable">
					<tr>
						<td id="mainContent">
							<div id="mainBody">
								<h2>Demo</h2>

								<p>See that cool-looking gadget in the bottom right corner of this window? <strong>That's ProveIt</strong>, and you can test drive it right here with any Wikipedia article. We've preloaded the article on Georgia Tech by default, but if you want to try a different one, just type the article name into the box below and click "Load article."</p>
								<form id="demoForm" action="">
									<fieldset>
										<label for="articleName">Wikipedia article name:</label>
										<input id="articleName" size="35" style="width: 300px;" value="Georgia Institute of Technology"/>
										<input id="articleBtn" type="submit" value="Load article"/>
										<p style="margin: 10px 0 20px 0;">More suggestions: <a href="#" onclick="loadArticle('Tech Tower')">Tech Tower</a> - <a href="#" onclick="loadArticle('ANAK Society')">ANAK Society</a> - <a href="#" onclick="loadArticle('Ramblin\' Wreck')">Ramblin' Wreck</a></p>

										<label for="wpTextbox1"><a id="articleLink"></a> article from Wikipedia:</label>
										<textarea rows="25" cols="115" style="width: 100%" id="wpTextbox1"></textarea>
									</fieldset>
								</form>
								<p>Wikipedia article used and made available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution/Share-Alike License 3.0 (Unported)</a>.</p>
							</div>
						</td>
					</tr>
				</table>
<?php include_once 'footer.php'; ?>
