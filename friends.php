
<style>caption {font-size: 1.7em; color: #F06; text-align: left;}
table {margin: 0; padding: 0; border-collapse: collapse; width: 100%;}
td, th {padding: 10px 4px; border-bottom: 1px solid #EEE;}
td + td {border-left: 1px solid #FAFAFA; color: #999;}
td + td + td {color: #666; border-left: none;}
td a {color: #444; text-decoration: none; text-align: right;}
td a, th a {display: block; width: 100%;}
td a:hover {background: #444; color: #FFF;}
tfoot th {text-align: right;}
th {text-align: left;}
th + th {text-align: right;}
th + th + th {text-align: left;}
th a {color: #F06; text-decoration: none; font-size: 1.1em;}
th a:visited {color: #F69;}
th a:hover {color: #F06; text-decoration: underline;}
thead tr, tfoot tr {color: #555; font-size: 0.8em;}
tr {font: 12px sans-serif; background: url(./img/prettyinpink_row.png) repeat-x #F8F8F8; color: #666;}
tr:hover {background: #FFF;}</style>
<?php
  $app_id = 'APP ID';
  $app_secret = 'APP SECRET KEY';
  $my_url = 'YOUR URL HERE';

  $code = $_REQUEST["code"];

 // auth user
 if(empty($code)) {
    $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id=' 
    . $app_id . '&redirect_uri=' . urlencode($my_url) ;
    echo("<script>top.location.href='" . $dialog_url . "'</script>");
  }

  // get user access_token
  $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
    . $app_id . '&redirect_uri=' . urlencode($my_url) 
    . '&client_secret=' . $app_secret 
    . '&code=' . $code;

  // response is of the format "access_token=AAAC..."
  $access_token = substr(file_get_contents($token_url), 13);

  // run fql query
  $fql_query_url = 'https://graph.facebook.com/'
    . 'fql?q=SELECT+pic_small,+name+FROM+user+WHERE+uid+IN(SELECT+uid2+FROM+friend+WHERE+uid1=me())+AND+is_app_user=1'
    . '&access_token=' . $access_token;
  $fql_query_result = file_get_contents($fql_query_url);
  $fql_query_obj = json_decode($fql_query_result, true);
  // display results of fql query
  echo "<table>";
echo "<h2>There are ".sizeof($fql_query_obj[data])." results. Showing 10 of YOUR FRIENDS who have used <a href='http://unstablecode.com/travel'>TRAVEL WITH FRIENDS</a></h2>";
for($i=0;$i<10;$i++)
{
echo '<tr><td><img src='.$fql_query_obj[data][$i][pic_small].' /></td><td>'.$fql_query_obj[data][$i][name].'</td><td>';
}
   echo "</table>";
?>