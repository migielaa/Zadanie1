<?php
//phpinfo();

include 'create_db.php';

$host = "localhost";
$user = "mig";
$passw = "qazwsx";
$DBname = "mig";
createDatabaseWithTables($DBname, $host, $user, $passw);


$url = $_REQUEST['url'];

echo " <link rel=\"stylesheet\" href=\"../styles/crawler.css\" type=\"text/css\">";
echo "<div class=\"form-container\">";
echo "        <h1>Crawler</h1>";
echo "       <form action=\"crawler.php\" method=\"post\">";
echo "          <input type=\"text\" class=\"data-input\" name=\"url\" value=$url>";
echo "          <input type=\"submit\" class=\"submit-button\" name=\"submit\" value=\"CRAWL!\">";
echo "    </form>";
echo "   </div>";

echo "    <script src=\"app/main.js\"></script>";

function insertIntoSiteToView($url)
{
    return "INSERT INTO sites_to_view (LINK_NAME) VALUES ('$url')";
}

function insertIntoSiteViewed($url, $content)
{
    return "INSERT INTO sites_viewed (LINK_NAME,PAGE_CONTENT) VALUES ('$url' , '$content')";
}

function crawler($url)
{
    global $host, $user, $passw;
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
    $values = array_unique($values);

    $conn = createConnection($host, $user, $passw);
    $sites_to_view = array('');
    $sites_to_view = getFromDb("sites_to_view", $conn);
//    $sites_viewed = getFromDb("sites_viewed",$conn);


    $pageContent = getPageContent($url)->saveXML();
    $pageContent = htmlspecialchars($pageContent);
    $pageContent = $conn->real_escape_string($pageContent);


    foreach ($values as $link) {
        if ($link !== '') {
            echo "<div class=\"result\" onclick=\"crawlOnUrlClick(this)\">'$link'</div>";

            if (!in_array($link, $sites_to_view)) {

                saveToDB(insertIntoSiteToView($link), $conn);
                saveToDB(insertIntoSiteViewed($link, $pageContent), $conn);
            }
        }

    }
    closeConnection($conn);

}

function getPageContent($url)
{
    @$doc = new DOMDocument();
    @$doc->loadHTML(file_get_contents($url));
    return @$doc;
}


function checkUrl($url)
{
    if (strpos($url, 'http') === false) {
        return false;
    }
    return filter_var($url, FILTER_VALIDATE_URL);
}

function getFromDb($tableName, $conn)
{
    return selectFromDatabase($tableName, $conn);
}

crawler($url)
?>