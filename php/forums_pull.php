<?php
ini_set('memory_limit','756M');

//$start_date = strtotime('2012-06-25');
//$end_date   = strtotime('2012-07-25');

$start_date = strtotime('2012-08-01');
$end_date   = strtotime('2012-08-23');

function write_file($id, $post, $date) {
    $file = "./forums/${id}:${date}.txt";
    $fh = fopen($file, 'w') or die("can't open file");

    $data = $post;
    fwrite($fh, $data);
    fclose($fh);
}

function get_data($shard, $start_date, $end_date) {
    $shard = str_pad($shard, 2, '0', STR_PAD_LEFT);
//echo $shard . "\n";
    try {
        $dbh = new PDO(
            "mysql:host=dbshard${shard}.ny4.com;port=3306;dbname=shard",
            'xxx',
            'yyy',
            array(
            )
        );
        // today's results
        //$sql = "
            //select forum_id,  title,   forum_thread_id,   forum_thread_post_id,   user_id
            //from forum_threads ft join forum_thread_posts ftp using (forum_thread_id)
            //where ft.forum_id in (5001819, 5001820, 5001821, 5001822, 5001823)
              //and ftp.create_date >= ${start_date}
              //and ftp.create_date < ${end_date}
             //";

//$sql = 'select * from forum_thread_posts where forum_thread_id=10699386';
//$sql = 'select * from forum_thread_posts where forum_thread_id=10793024';
$sql = 'select * from forum_threads where forum_thread_id = 11061441';

//echo $sql . "\n";
        $stmt = $dbh->prepare($sql);
        // call the stored procedure
        $params = array();
        $stmt->execute($params);

        $article_ids = array();
        $day_results = array();
        $count = 0;
        $results = '';
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $count++;
            //$results .= implode(',', $r);
        }
        //return $results;
        echo "shard ${shard}\t results: ${count}\n";
    }
    catch (Exception $e) {
        echo "can't connect to shard ${shard}\n";
    }
}



//$shards = range(1, 40, 1);
$shards = range(2, 40, 2);
//$shards = range(2, 6, 2);
$r = array();
foreach ($shards as $shard) {
    get_data($shard, $start_date, $end_date);
//die();
}

