<?php
ini_set('memory_limit','756M');

$ts = 1327372209; // time();
$ds = date('Y-m-d',  $ts);
$today_start = strtotime($ds);
$tomorrow_start = strtotime(date("Y-m-d", strtotime("$ds +1 days")));
$week_ago_start = $today_start - (86400 * 7);

try {
    $dbh = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=aux',
        'yyy',
        'XXX$',
        array(
        )
    );
    // today's results
    $sql = "SELECT DATE_FORMAT(FROM_UNIXTIME(edka.create_date -  3600 * 5), '%H') AS `rollup`, edka.kb_article_id AS `article_id`, COUNT(*) AS `count`
            FROM email_depot_kb_articles edka
            LEFT JOIN kb_articles ka USING (kb_article_id)
            WHERE edka.status IN ('sent','closed') AND ka.media_type='email' AND " . $today_start . " <= edka.create_date AND " . $tomorrow_start . " > edka.create_date
              AND topic_id in (293561536,293562538,293566628,294731813,294736805,294737703,298602026,299563109,300190084,301185785,330488952,398942702,428665400,428668248,428674540,428675212,428677180,429043149,429046821,429048003,429048559,429051117,429052751,429058293,429058723,523039242,540339893,815171221,815217309,815255643,832591197,852030005)
            GROUP BY `rollup`, edka.kb_article_id
            ORDER BY `rollup` ASC, `count` DESC";

    $stmt = $dbh->prepare($sql);
    // call the stored procedure
    $params = array();
    $stmt->execute($params);

    $article_ids = array();
    $day_results = array();
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hour =       (int)$r['rollup'];
        $count =      (int)$r['count'];
        $article_id = (int)$r['article_id'];
        $day_results[$hour][$article_id] = $count;

        if (!in_array($article_id, $article_ids)) {
            $article_ids[] = $article_id;
        }
    }

    sort($article_ids);

    // find averages, this is 7 day windows averaged per article per hour
    $sql = "SELECT DATE_FORMAT(FROM_UNIXTIME(edka.create_date -  3600 * 5), '%H') AS `rollup`, edka.kb_article_id AS `article_id`, ROUND(COUNT(*)/7) AS `count`
            FROM email_depot_kb_articles edka
            LEFT JOIN kb_articles ka USING (kb_article_id)
            WHERE edka.status IN ('sent','closed') AND ka.media_type='email' AND " . $week_ago_start . " <= edka.create_date AND " . $today_start . " > edka.create_date
            GROUP BY `rollup`, edka.kb_article_id
            HAVING `count` > 0
            ORDER BY `rollup` ASC, `count` DESC";

    $stmt = $dbh->prepare($sql);
    // call the stored procedure
    $params = array();
    $stmt->execute($params);

    $average_results = array();
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $average_results[(int)$r['rollup']][(int)$r['article_id']] = (int)$r['count'];
    }

    $chart = array();

$max_value   = 0;
$max_article = 0;

    foreach (range(0, 23) as $hour) {
        foreach ($article_ids as $article_id) {
            $day_value = isset($day_results[$hour][$article_id]) ? $day_results[$hour][$article_id] : 0;
            $average_value = isset($average_results[$hour][$article_id]) ? $average_results[$hour][$article_id] : 0;
            $value = $day_value - $average_value;
            $chart[$hour][$article_id] = $value;
            if ($value > $max_value) {
                $max_value = $value;
                $max_article = $article_id;
            }
        }
    }
var_dump(count($article_ids));
//var_dump($max_article, $max_value);
//die();

$csv = '#,'. implode(',', $article_ids) ."\n";
foreach ($chart as $hour => $values) {
    $csv .= $hour . ',' . implode(',', $values) . "\n";
}

echo $csv . "\n\n";

}




catch (Exception $e) {
    var_dump($e);
}


