<?php

include 'create_db.php';

$host="localhost";
$user="root";
$DBname="crawlerDB";
createDatabaseWithTables($DBname,$host,$user);


$url = $_REQUEST['url'];

echo " <link rel=\"stylesheet\" href=\"../styles/crawler.css\" type=\"text/css\">";
echo "<div class=\"form-container\">";
echo "        <h1>Crawler</h1>";
echo "       <form action=\"crawler.php\" method=\"post\">";
echo "          <input type=\"text\" class=\"data-input\" name=\"url\" value=$url>";
echo "          <input type=\"submit\" class=\"submit-button\" name=\"submit\" value=\"CRAWL!\">";
echo "    </form>";
echo "   </div>";

function insertIntoSiteToView($url){
    return "INSERT INTO sites_to_view (url) VALUES ('$url')";
}

function insertIntoSiteViewed($url,$content){
    return "INSERT INTO sites_viewed (url,page_content) VALUES ('$url,$content')";
}

function crawler($url)
{
    global $host,$user;
    if (checkUrl($url) == false) {
        return "Please provide correct url";
    }

    $url = strpos($url, "#") ? substr($url, 0, strpos($url, "#")) : $url;
    @$doc = new DOMDocument();
    @$doc->loadHTML(file_get_contents($url));

    $listOfLinks = @$doc->getElementsByTagName("a");
    $values[] = ("");
    foreach ($listOfLinks as $link) {
        $singleValue = $link->getAttribute("href");
        $singleValue = checkUrl($singleValue);

        if ($singleValue) $values[] = $singleValue;
    }
    foreach ($values as $link) {
        if($link !=='') {
            echo "<mark>$link</mark>&ensp;&ensp;";

            $conn = createConnection($host, $user);
            saveToDB(insertIntoSiteToView($link), $conn);
            saveWithContent(insertIntoSiteViewed($link), $conn);
            closeConnection($conn);
        }
    }

}
function saveWithContent($url,$conn){
    @$doc = new DOMDocument();
    @$doc->loadHTML(file_get_contents($url));
    saveToDB(insertIntoSiteViewed($url,$doc),$conn);
}
function checkUrl($url)
{
    if (strpos($url, 'http' ) === false){
        return false;
    }
    return filter_var($url, FILTER_VALIDATE_URL);
}

crawler($url)
?>