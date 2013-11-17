<?php
ini_set('memory_limit','756M');


try {
    $dbh = new PDO(
        'mysql:host=127.0.0.1;port=3306;dbname=interview',
        'xxx',
        'yyyy',
        array(
        )
    );
    // today's results
    $sql = "
select kb_article_revision_id, article
from kb_article_revisions
where article like '%http://www.etsy.com/help/article/%' and site='cs'
order by kb_article_revision_id;
";

    $stmt = $dbh->prepare($sql);
    // call the stored procedure
    $params = array();
    $stmt->execute($params);

    $revisions = array();

    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $revisions[$r['kb_article_revision_id']] = $r['article'];
    }

    foreach ($revisions as $revision_id => $article) {
echo $article;
echo "\n--------------\n";
        $matches = array();
        $search  = '/http:\/\/www.etsy.com\/help\/article\/(\d+)\?utm_source=compass&utm_medium=email(#[\w]+)?/';
//preg_match_all($search, $article, $matches);
//var_dump($matches[0]);
        $replace = '%faq_$1$2%';
        $updated_article = preg_replace($search, $replace, $article);

echo $updated_article;
echo "\n=======================\n";
    }

} catch (Exception $e) {
    var_dump($e);
}


