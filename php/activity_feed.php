<?php
//ini_set('memory_limit','756M');

// cc3107
// u 12069433
// s 6126656

//chucklarge
// u 8459833
// s 6096592


$owner_id=8459833;

function get_data($shard, $owner_id) {
    $shard = str_pad($shard, 2, '0', STR_PAD_LEFT);
    try {
        $dbh = new PDO(
            "mysql:host=dbshard${shard}.ny4.com;port=3306;dbname=shard",
            'xxx',
            'yyy',
            array(
            )
        );
        // today's results
        //$sql = "select * from activity where owner_id={$owner_id}";
        $sql = "select count(*) as `c` from activity where owner_id={$owner_id}";
        $stmt = $dbh->prepare($sql);
        // call the stored procedure
        $params = array();
        $stmt->execute($params);

        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "shard ${shard}\t results: " . $r['c'] . " \n";
            //echo "shard ${shard}\t results: " . implode(', ', $r) . " \n";
            break;
        }
    }
    catch (Exception $e) {
        echo "can't connect to shard ${shard}\n";
    }
}


$shards = range(2, 40, 2);
foreach ($shards as $shard) {
    get_data($shard, $owner_id);
}
