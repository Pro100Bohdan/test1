<?php

class Model_Admin extends Model {

    public function tableCopyMax($tool, $maxId) {
         $copy = mysql_query(' SELECT id, qe, invoice, stamping, partnumber, ordernumber, barcode, quantity, lils, dt, zakaz_number, box_quant, notes, arrive, browser, user, quantity FROM r_invoices WHERE id > '.$maxId);
        $copy = mysql_fetch_assoc($copy);
        $rest = substr($copy['partnumber'], 0, 3);
        if (($rest == 'MFE') && ($tool == 'steel_packing')) {
            return;
        }
        $result = mysql_query('INSERT INTO '.$tool.' (id, qe, invoice, stamping, partnumber, ordernumber, barcode, quantity, lils, dt, zakaz_number, box_quant, notes, arrive, browser, user, difflils) VALUES ("'.$copy['id'].'", "'.$copy['qe'].'", "'.$copy['invoice'].'", "'.$copy['stamping'].'", "'.$copy['partnumber'].'", "'.$copy['ordernumber'].'", "'.$copy['barcode'].'", "'.$copy['quantity'].'", "'.$copy['lils'].'", "'.$copy['dt'].'", "'.$copy['zakaz_number'].'", "'.$copy['box_quant'].'", "'.$copy['notes'].'", "'.$copy['arrive'].'", "'.$copy['browser'].'", "'.$copy['user'].'", "'.$copy['quantity'].'")');


    }

    public function tableCopy($tool) {

        $checkId = mysql_query('SELECT MAX(id) as maxId FROM '.$tool. ' ');
        $row = mysql_fetch_assoc($checkId);

        if ($row['maxId']) {
            $maxId = $row['maxId'];
        } else {
            $maxId = 0;
        }

        $invoiceId = mysql_query('SELECT MAX(id) as maxId FROM r_invoices ');
        $rowinv = mysql_fetch_assoc($invoiceId);

        if ($rowinv['maxId']) {
            $maxIdinvoice = $rowinv['maxId'];
        } else {
            $maxIdinvoice = 0;
        }
        $numId = $maxIdinvoice - $maxId;

        for ($i=0; $i < $numId ; $i++) {
            $this->tableCopyMax($tool, $maxId);
            $maxId++;
        }


       // $copy = mysql_query('INSERT INTO '.$tool.' (id, qe, invoice, stamping, partnumber, ordernumber, barcode, quantity, lils, dt, zakaz_number, box_quant, notes, arrive, browser, user, difflils) SELECT id, qe, invoice, stamping, partnumber, ordernumber, barcode, quantity, lils, dt, zakaz_number, box_quant, notes, arrive, browser, user, quantity FROM r_invoices WHERE id > '.$maxId);
        //$copy = mysql_query('INSERT INTO '.$tool.' SELECT * FROM r_invoices WHERE id > '.$maxId);


    }

    public function invoices($tool, $page) {

        $tool = mb_strtolower(str_replace('-', '_', $tool));

        $this->tableCopy($tool);

        /*$result['paginator'] = mysql_query('SELECT COUNT(id) as cnt FROM '.$tool.' WHERE difflils > 0 AND invoice LIKE "%'.$_GET['invoice'].'%" AND stamping LIKE "%'.$_GET['stamping'].'%" AND partnumber LIKE "%'.$_GET['partnumber'].'%" AND barcode LIKE "%'.$_GET['barcode'].'%" AND quantity LIKE "%'.$_GET['quantity'].'%" AND ordernumber LIKE "%'.$_GET['ordernumber'].'%"');

        $result['query'] = mysql_query('SELECT * FROM '.$tool.' WHERE difflils > 0 AND invoice LIKE "%'.$_GET['invoice'].'%" AND stamping LIKE "%'.$_GET['stamping'].'%" AND partnumber LIKE "%'.$_GET['partnumber'].'%" AND barcode LIKE "%'.$_GET['barcode'].'%" AND quantity LIKE "%'.$_GET['quantity'].'%" AND ordernumber LIKE "%'.$_GET['ordernumber'].'%" ORDER BY id DESC LIMIT '.$page.', 50');*/


        if ($_GET['submit']) {

            $result['paginator'] = mysql_query('SELECT COUNT(id) as cnt FROM '.$tool.' WHERE difflils > 0 AND invoice LIKE "%'.$_GET['invoice'].'%" AND stamping LIKE "%'.$_GET['stamping'].'%" AND partnumber LIKE "%'.$_GET['partnumber'].'%" AND barcode LIKE "%'.$_GET['barcode'].'%" AND quantity LIKE "%'.$_GET['quantity'].'%" AND ordernumber LIKE "%'.$_GET['ordernumber'].'%"');

            $result['query'] = mysql_query('SELECT * FROM '.$tool.' WHERE difflils > 0 AND invoice LIKE "%'.$_GET['invoice'].'%" AND stamping LIKE "%'.$_GET['stamping'].'%" AND partnumber LIKE "%'.$_GET['partnumber'].'%" AND barcode LIKE "%'.$_GET['barcode'].'%" AND quantity LIKE "%'.$_GET['quantity'].'%" AND ordernumber LIKE "%'.$_GET['ordernumber'].'%" ORDER BY id DESC LIMIT 0, 50');

        } else {
            $this->tableCopy($tool);

            $result['paginator'] = mysql_query('SELECT COUNT(id) as cnt FROM '.$tool.' WHERE difflils > 0');
            $result['query'] = mysql_query('SELECT * FROM '.$tool.' WHERE difflils > 0 ORDER BY id DESC LIMIT '.$page.', 50');
        }



        return $result;
    }

    function get_users() {

        $this->db->connect();

        $result = mysql_query('SELECT * FROM user_keys');

        if ($result) {

            $pos = 1;

            while ($row = mysql_fetch_assoc($result)) {

                $data[$pos] = $row;
                $data[$pos]['pos'] = $pos;

                $pos++;

            }

        }

        $this->db->close();

        return $data;

    }

    /*function get_user($id) {

        $this->db->connect();

        $result = mysql_query('SELECT * FROM user_keys WHERE key_id='.$id);

        if ($result) {

            $data = mysql_fetch_assoc($result);

        }

        $this->db->close();

        return $data;

    }
*/
    function new_user($user) {

        $this->db->connect();

        $result = mysql_query('INSERT INTO user_keys (login, enter_key, rights) VALUES ("'.$user['login'].'", "'.$user['key'].'", '.$user['rights'].')');

        if ($result) {

            $data['status'] = true;

        } else {

            $data['status'] = false;

        }

        $this->db->close();

        return $data;

    }

    function get_keys() {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM user_keys');

        if ($res) {

            while ($row = mysql_fetch_assoc($res)) {

                $data['keys'][] = $row;

            }

        }

        $this->db->close();

        return $data;

    }

    function random() {

        $str = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';

        for ($i = 0; $i < 12; $i++) {

            $data['key'] .= $str[rand(0, strlen($str) - 1)];

        }

        return $data;

    }

    function save($user) {

        $this->db->connect();

        if (mysql_query('UPDATE user_keys SET enter_key="'.$user['key'].'" WHERE key_id='.$user['id'])) {

            $data['status'] = true;

        } else {

            $data['status'] = false;

        }

        $this->db->close();

        return $data;

    }

    function get_key($id) {

        $this->db->connect();

        $res = mysql_query("SELECT key_id, title_key, enter_key FROM user_keys WHERE key_id=".(int) $id);

        if ($res) {

            $data = mysql_fetch_assoc($res);

        }

        $this->db->close();

        return $data;

    }

    function get_file($id, $filename) {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM orders WHERE package_id=' . (int) $id . ' ORDER BY order_id DESC');

        // $res = mysql_query('SELECT * FROM package LEFT JOIN orders USING (package_id) WHERE package.package_id="'.$id.'" ORDER BY order_id DESC');

        if ($res) {

            while ($row = mysql_fetch_assoc($res)) {

                $data['orders'][] = $row;

            }

            $data['package'] = $filename;
            $data['date'] = date('d.m.Y');




            /*while ($row = mysql_fetch_assoc($res)) {

                $orders[] = array(

                    iconv('utf-8', 'windows-1251', $row['order_number']),
                    iconv('utf-8', 'windows-1251', $row['part_number']),
                    iconv('utf-8', 'windows-1251', $row['laser_marking']),
                    iconv('utf-8', 'windows-1251', $row['material']),
                    iconv('utf-8', 'windows-1251', $row['color']),
                    iconv('utf-8', 'windows-1251', $row['quantity']),
                    iconv('utf-8', 'windows-1251', $row['demension']),
                    iconv('utf-8', 'windows-1251', $row['note']),
                    iconv('utf-8', 'windows-1251', $row['date'])

                );

            }

            $file = fopen($filename . '.csv', 'w');

            fputcsv($file, array('Package Number', $filename, '', 'Issue date/ Datum vystaven: '.date('d.m.Y')), ';');
            fputcsv($file, array('Issue date/ Datum vystaven: '.date('d.m.Y'), '', '', 'Adresa doruиenн/Ship to:'), ';');
            fputcsv($file, array('address', '', '', 'address'), ';');
            fputcsv($file, array('address', '', '', 'city'), ';');
            fputcsv($file, array('VAT'), ';');
            fputcsv($file, array('TEL'), ';');
            fputcsv($file, array('Person'), ';');
            fputcsv($file, array('Order Number', 'Part Number', 'Laser Number', 'Material', 'Color', 'Quantity', 'Demension', 'Note', 'Date'), ';');

            foreach ($orders as $fields) {

                fputcsv($file, $fields, ';');

            }

            fclose($file);

            $data = true;*/

        } else {

            $data = 'File is not generated.';

        }

        $this->db->close();

        return $data;

    }

    /*function deletePackage($package)
    {
        $this->db->connect();

        $orders  = mysql_query('DELETE FROM orders WHERE package_id='.$package);
        $package = mysql_query('DELETE FROM package WHERE package_id='.$package);

        if ($orders && $package)
        {
            return ['status' => true];
        } else {
            return ['status' => false];
        }

        $this->db->close();
    }
*/
    function to_archive($package) {

        if (!$package) {
            return array('status' => false);
        } else {

            $this->db->connect();

            $result = mysql_query('UPDATE package SET archive=1 WHERE package_id='.$package);

            $this->db->close();

            if ($result) {
                return array('status' => true);
            } else {
                return array('status' => false);
            }

        }

        //return $data;

    }

    function out_archive($package)
    {
        $this->db->connect();

        if (mysql_query('UPDATE package SET archive=0 WHERE package_id='.$package))
        {
            return array('status' => true);
        } else {
            return array('status' => false);
        }

        $this->db->close();
    }

    function new_package($new) {

        $this->db->connect();

        $date_num = date('Ymd');

        if (mysql_query('INSERT INTO package (package_number, date, tool, time, date_num, user) VALUES ("'.$new['package_number'].'", "'.date('Y-m-d').'", "'.str_replace(' ', '-', $new['tool']).'", "'.time().'", '.$date_num.', "'.$_SESSION['barcode-service']['user_name'].'")')) {

            $res2 = mysql_query('SELECT LAST_INSERT_ID() AS max FROM package');
            $max = mysql_fetch_assoc($res2);


            $data['max'] = $max['max'];
            $data['status'] = true;

        } else {

            $data['status'] = false;

        }

        $this->db->close();

        return $data;

    }

    function new_order($new) {

        // Если пользователь указал баркод, то этот баркод проверяется в БД, если такого баркода нет, значит программа добавляет парт_намбер и баркод в базу как новый.


        $this->db->connect();

        //$res = mysql_query('INSERT INTO orders (author, order_number, part_number, laser_marking, material, color, quantity, demension, date, note, tool, package_id) VALUES ("'.$_SESSION['barcode-service']['user_name'].'", "'.$new['order_number'].'", "'.$new['part_number'].'", "'.$new['laser_marking'].'", "'.$new['material'].'", "'.$new['color'].'", "'.$new['quantity'].'", "'.$new['demension'].'", "'.date('Y-m-d').'", "'.$new['note'].'", "'.$new['tool'].'", "'. (int) $new['package_id'] .'")');

        $result = mysql_query('INSERT INTO orders (time, author, order_number, part_number, laser_Marking, material, color, quantity, demension, date, package_id, note) VALUES ('.time().', "'.$_SESSION['barcode-service']['user_name'].'", "'.$new['order_number'].'", "'.$new['part_number'].'", "'.$new['laser_marking'].'", "'.$new['material'].'", "'.$new['color'].'", "'.$new['quantity'].'", "'.$new['demension'].'", "'.date('Y-m-d').'", '.$new['package_id'].', "'.$new['note'].'")');





        $scan = mysql_query('SELECT * FROM barcode WHERE part_number="'.$new['part_number'].'"');

        $rows = mysql_num_rows($scan);
        $row = mysql_fetch_assoc($scan);

        if (!$rows) {

            mysql_query('INSERT INTO barcode (barcode, part_number) VALUES ("'.$new['barcode'].'", "'.$new['part_number'].'")');
            mysql_query('INSERT INTO material (part_number, material, dm2) VALUES ("'.$new['part_number'].'", "'.$new['material'].'", "'.$new['demension'].'")');

        } else {

            if ($row['barcode'] == '') {

                mysql_query('UPDATE barcode SET barcode='.$new['barcode'].' WHERE part_number="'.$new['part_number'].'"');

            }

        }

        if ($result) {

            $data = true;

        } else {

            $data = false;

        }

        return $data;

    }

    function save_invoice($invoice) {
        $this->db->connect();

        $diffLils = $invoice['difflils'] - $invoice['lils'];

        $tool = mb_strtolower(str_replace('-', '_', $invoice['tool']));

        $update = mysql_query('UPDATE '.$tool.' SET difflils='.$diffLils.', notes="'.$invoice['notes'].'", user="'.$_SESSION['barcode-service']['user_name'].'" WHERE id='.$invoice['id']);

        $save = mysql_query('INSERT INTO statistic (user, ordernumber, partnumber, stamping, lils, sector, date) VALUES ("'.$_SESSION['barcode-service']['user_name'].'", "'.$invoice['ordernumber'].'", "'.$invoice['partnumber'].'", "'.$invoice['stamping'].'", "'.$invoice['lils'].'", "'.$invoice['tool'].'", "'.time().'")');

        if ($update) {

            $callBack = mysql_query('SELECT difflils, notes FROM '.$tool.' WHERE id='.$invoice['id']);

            $row = mysql_fetch_assoc($callBack);

            $data['difflils'] = $row['difflils'];
            $data['notes'] = $row['notes'];
            $data['author'] = $_SESSION['barcode-service']['user_name'];

            if ($data['difflils'] <= 0) {
                $data['excelent'] = true;
            }

            $data['status'] = true;
        } else {
            $data['status'] = false;
        }

        $this->db->close();

        return $data;
    }

    // ###################################################

    function get_invoices($tool, $page) {

        $page = $page * 50;

        $this->db->connect();

        $result = $this->invoices($tool, $page);

        $row = mysql_fetch_assoc($result['paginator']);
        $data['cnt'] = floor($row['cnt'] / 50 + 2);
        $data['tool'] = $tool;



        if ($result['query']) {

            while ($row = mysql_fetch_assoc($result['query'])) {
                $data['tr'][] = $row;
            }
        }

        return $data;
    }



    function get_user_statistic_2($user) {
        $this->db->connect();

        /*$thisTime = getdate(time());

        $searchTime = mktime(

            $thisTime['hours'] - $_GET['orderTime'],
            $thisTime['minutes'],
            $thisTime['seconds'],
            $thisTime['mon'],
            $thisTime['mday'],
            $thisTime['year']

        );

        $orderTime = 'date > '.$searchTime.'';

        if (!$_GET['orderTime']) {
            $orderTime = '';
        }

        if (!$_GET['sector']) {
            $sector = '';
        }*/

        $user = str_replace('%20',' ',$user);
        $result = mysql_query('SELECT * FROM statistic WHERE user="'.$user.'"');

        if ($result) {

            $pos = 1;

            while ($row = mysql_fetch_assoc($result)) {
                $data['tr'][$pos] = $row;
                $data['tr'][$pos]['pos'] = $pos;
                $data['tr'][$pos]['date'] = $this->getTime($data['tr'][$pos]['date']);
                $pos++;
            }
        }

        $this->db->close();

        return $data;
    }

    function get_user_statistic_order_2($user, $tool) {
        $this->db->connect();

        /*$thisTime = getdate(time());

        $searchTime = mktime(

            $thisTime['hours'] - $_GET['orderTime'],
            $thisTime['minutes'],
            $thisTime['seconds'],
            $thisTime['mon'],
            $thisTime['mday'],
            $thisTime['year']

        );

        $orderTime = 'date > '.$searchTime.'';

        if (!$_GET['orderTime']) {
            $orderTime = '';
        }

        if (!$_GET['sector']) {
            $sector = '';
        }*/


        $result = mysql_query('SELECT * FROM orders LEFT JOIN package USING(package_id) WHERE author="'.$user.'" AND package_id='.$tool);

        if ($result) {

            $pos = 1;

            while ($row = mysql_fetch_assoc($result)) {
                $data['tr'][$pos] = $row;
                $data['tr'][$pos]['time'] = $this->getTime($row['time']);
                $pos++;
            }
        }

        $this->db->close();

        return $data;
    }

    function get_archive() {

        $this->db->connect();

        $result = mysql_query('SELECT * FROM package WHERE archive=1');

        $this->db->close();

        if ($result) {

            while ($row = mysql_fetch_assoc($result)) {

                $data['tr'][] = $row;

            }

            return $data;

        }

    }

    function get_statistic_orders($date1=0,$date2=0) {

        $this->db->connect();

        //$result = mysql_query('SELECT *, COUNT(package_id) AS cnt FROM orders GROUP BY author');

        $orderBy = $_GET['order'];

        $thisTime = getdate(time());

        $searchTime = mktime(

            $thisTime['hours'] - $_GET['orderTime'],
            $thisTime['minutes'],
            $thisTime['seconds'],
            $thisTime['mon'],
            $thisTime['mday'],
            $thisTime['year']

        );

        $orderTime = 'WHERE package.time > '.$searchTime.'';

        if (!$_GET['orderTime']) {
            $orderTime = '';
        }

        if (!$_GET['order']) {
            $orderBy = 'package_id, user';
        }

        if ($date1) {

            $where = ' WHERE  package.date >= "'.(int)str_replace('-', '', $date1).'" AND orders.time >="'.(int)strtotime($date1).'"';

        }

        if ($date2) {

            $where = ' WHERE package.date_num >= "'.(int)str_replace('-', '', $date1).'" AND package.date_num <= "'.(int)str_replace('-', '', $date2).'" AND orders.time >="'.(int)strtotime($date1).'" AND orders.time <="'.(int)strtotime($date2).'" ';

        }


        $result = mysql_query('SELECT *, COUNT(order_id) AS cnt, SUM(quantity) AS sum FROM package LEFT JOIN orders USING(package_id) '.$where.'  GROUP BY '.$orderBy.'');

        $data['order_by'] = $orderBy;

        if($result) {

            $pos = 1;

            while($row = mysql_fetch_assoc($result)) {
                $data['tr'][$pos] = $row;
                $data['tr'][$pos]['pos'] = $pos;
                $pos++;
            }

            return $data;

        }

        $this->db->close();
    }
    function get_search_orders($date1=0,$date2=0,$pags=1) {

        $this->db->connect();



        if ($date1) {

            $where = ' orders.time >="'.(int)strtotime($date1).'"';

            $wheres=" WHERE ";
        }

        if ($date2) {

            $where = ' orders.time >="'.(int)strtotime($date1).'" AND  orders.time <="'.(int)strtotime($date2).'" ';
            $wheres=" WHERE ";
            $addand="AND";
        }

        if ($_GET['laser_marking']) {

                $addWhere2 = ''.$addand.' laser_marking LIKE "%'.$_GET['laser_marking'].'%"';
                $wheres=" WHERE ";
            $addand="AND";

        }

        if ($_GET['author']) {

            $addWhere3 = ''.$addand.' author LIKE "%'.$_GET['author'].'%"';
            $wheres=" WHERE ";
            $addand="AND";
        }
        if ($_GET['partnumber']) {

            $addWhere4 = ''.$addand.' part_number LIKE "%'.$_GET['partnumber'].'%"';
            $wheres=" WHERE ";

        }
        if ($_GET['tool']!=='All') {

            $addWhere5 = ''.$addand.' tool LIKE "%'.$_GET['tool'].'%"';
            $wheres=" WHERE ";
            $addand="AND";

        }
if($_GET['view']){
            if( $_GET['view'] == 'All'){
                $count = 1000000000;
            }else {
                $count = $_GET['view'];
            }
}else{
    $count = 50;
}
        $kol = $count;  //количество записей для вывода
        $art = ($pags * $kol) - $kol; // определяем, с какой записи нам выводить

        $result = mysql_query("SELECT *, orders.date AS data_1 FROM orders LEFT JOIN package USING(package_id) $wheres $where  $addWhere $addWhere2  $addWhere3 $addWhere4  $addWhere5 LIMIT $art,$kol ");

        $res = mysql_query("SELECT COUNT(*) FROM `orders` LEFT JOIN package USING(package_id) $wheres $where  $addWhere $addWhere2  $addWhere3  $addWhere4    $addWhere5  LIMIT $art,$kol  ");
        $row = mysql_fetch_row($res);
        $total = $row[0]; // всего записей
        $res2 = mysql_query("SELECT SUM(quantity) FROM `orders` LEFT JOIN package USING(package_id) $wheres $where  $addWhere  $addWhere2  $addWhere3 $addWhere4   $addWhere5 ");
        $row2 = mysql_fetch_row($res2);
        $total2 = $row2[0]; // всего записей

$data['alls']= $total ;
        $data['alls2']= $total2 ;
        if($result) {

            $pos = 1;

            while($row = mysql_fetch_assoc($result)) {
                $data['tr'][$pos] = $row;
                $data['tr'][$pos]['pos'] = $pos;
                $pos++;
            }

            return $data;

        }

        $this->db->close();
    }
    function get_search_statistic($date1=0,$date2=0,$pags=1) {

        $this->db->connect();



        if ($date1) {

            $where = ' date >="'.(int)strtotime($date1).'"';
            $addand="AND";
            $wheres=" WHERE ";
        }

        if ($date2) {

            $where = 'date >="'.(int)strtotime($date1).'" AND  date <="'.(int)strtotime($date2).'" ';
            $addand="AND";
            $wheres=" WHERE ";
        }
        if ($_GET['tool']) {
            if ($_GET['tool'] != 'All') {
                $addWhere = ''.$addand.' sector="'.$_GET['tool'].'"';
                $wheres=" WHERE ";
                $addand="AND";
            }
        }
        if ($_GET['author']) {

            $addWhere3 = ''.$addand.' user LIKE "%'.$_GET['author'].'%"';
            $wheres=" WHERE ";
            $addand="AND";
        }

        if ($_GET['partnumber']) {

            $addWhere2 = ''.$addand.' partnumber LIKE "%'.$_GET['partnumber'].'%"';
            $wheres=" WHERE ";
            $addand="AND";
        }

        if ($_GET['stamping']) {

            $addWhere4 = ''.$addand.' stamping='.$_GET['stamping'];
            $wheres=" WHERE ";
            $addand="AND";
        }

        if($_GET['view']){
            if( $_GET['view'] == 'All'){
                $count = 1000000000;
            }else {
                $count = $_GET['view'];
            }
        }else{
            $count = 50;
        }
        $kol = $count;  //количество записей для выводаа
        $art = ($pags * $kol) - $kol; // определяем, с какой записи нам выводить

        $result = mysql_query("SELECT * FROM statistic $wheres $where  $addWhere $addWhere2 $addWhere3 $addWhere4 LIMIT $art,$kol ");

        $res = mysql_query("SELECT COUNT(*) FROM `statistic` $wheres $where  $addWhere $addWhere2 $addWhere3 $addWhere4 LIMIT $art,$kol");
        $row = mysql_fetch_row($res);
        $total = $row[0]; // всего записей
        $res2 = mysql_query("SELECT SUM(lils) FROM `statistic` $wheres $where  $addWhere $addWhere2 $addWhere3 $addWhere4");
        $row2 = mysql_fetch_row($res2);
        $total2 = $row2[0]; // всего записей
        $data['alls']= $total;

        $data['alls2']= $total2;

        if($result) {

            $pos = 1;

            while($row = mysql_fetch_assoc($result)) {
                $data['tr'][$pos] = $row;
                $data['tr'][$pos]['pos'] = $pos;
                $pos++;
            }

            return $data;

        }

        $this->db->close();
    }

    function get_statistic($date1,$date2) {

        $this->db->connect();

        //$result = mysql_query('SELECT *, COUNT(package_id) AS cnt FROM orders GROUP BY author');

        $orderBy = $_GET['order'];

        $thisTime = getdate(time());

        $searchTime = mktime(

            $thisTime['hours'] - $_GET['orderTime'],
            $thisTime['minutes'],
            $thisTime['seconds'],
            $thisTime['mon'],
            $thisTime['mday'],
            $thisTime['year']

        );

        $orderTime = 'WHERE date > '.$searchTime.'';

        if (!$_GET['orderTime']) {
            $orderTime = '';
        }

        if (!$_GET['order']) {
            $orderBy = 'user, sector';
        }
        if ($date1) {

            $where = ' WHERE  date >= "'.(int)$date1.'"';

        }

        if ($date2) {

            $where = ' WHERE date >= "'.(int)$date1.'" AND date <= "'.(int)$date2.'" ';

        }
        $result = mysql_query('SELECT user, sector, ordernumber, partnumber, SUM(lils) AS sum_lils FROM statistic '.$orderTime.'   '.$where.' GROUP BY '.$orderBy.'');

        $data['order_by'] = $orderBy;

        if($result) {

            $pos = 1;

            while($row = mysql_fetch_assoc($result)) {
                $data['tr'][$pos] = $row;
                $data['tr'][$pos]['pos'] = $pos;
                $pos++;
            }

            return $data;

        }

        $this->db->close();
    }

    function get_user_statistic($author, $tool) {
        $this->db->connect();

        if ($tool) {
            if ($tool != 'All') {
                $addWhere = ' AND package_id IN (SELECT package_id FROM package WHERE tool="'.$tool.'")';
            }
        }

        $result = mysql_query('SELECT * FROM orders LEFT JOIN package USING(package_id) WHERE author="'.$author.'"'.$addWhere);

        if ($result) {
            $pos = 1;
            while($row = mysql_fetch_assoc($result)) {
                $data['orders'][$pos] = $row;
                $data['orders'][$pos]['pos'] = $pos;
                $pos++;
            }

            $data['cnt'] = mysql_num_rows($result);

            return $data;

        }

        $this->db->close();
    }

    function get_packages($tool, $date1, $date2) {

        if ($tool) {

            $where = ' WHERE tool="'.$tool.'" AND archive=0';

            if ($date1) {

                $where = ' WHERE tool="'.$tool.'" AND date_num >= "'.(int)$date1.'" AND archive=0';

            }

            if ($date2) {

                $where = ' WHERE tool="'.$tool.'" AND date_num >= "'.(int)$date1.'" AND date_num <= "'.(int)$date2.'" AND archive=0';

            }

        } else {

            $where = ' WHERE archive=0';

            if ($date1) {

                $where = ' WHERE date_num >= "'.(int)$date1.'" AND archive=0';

            }

            if ($date2) {

                $where = ' WHERE date_num >= "'.(int)$date1.'" AND date_num <= "'.(int)$date2.'" AND archive=0';

            }

        }

        $this->db->connect();

   //     $paginator = mysql_query('SELECT COUNT(package_id) AS cnt FROM package'.$where.'');

      //  $row = mysql_fetch_assoc($paginator);

     //   $data['cnt'] = floor($row['cnt'] / 50 + 2);

     //   $res = mysql_query('SELECT * FROM package' . $where. ' ORDER BY time DESC');
        $res = mysql_query('SELECT * FROM package ' . $where. ' ORDER BY time DESC');
        if ($res) {

            $pos = 1;

            while ($row = mysql_fetch_assoc($res)) {

                $data['tr'][$pos] = $row;
                $data['tr'][$pos]['time'] = $this->getTime($data['tr'][$pos]['time']);

                $pos++;
                /*$data[] = array(

                    'id'             => $row['package_id'],
                    'package_number' => $row['package_number'],
                    'date'           => $row['date'],
                    'tool'           => $row['tool']

                );*/

            }

        } else {

            $data = false;

        }

        $this->db->close();

        $data['tool'] = $tool;

        return $data;

    }

    function get_orders($package) {

        $this->db->connect();
        $date1 = $_GET['date-1'];
        //
        $date2 = $_GET['date-2'];
        if ($date1) {

            $where = 'AND   orders.time >="'.(int)strtotime($date1).'"';

        }
        if ($date2) {

            $where = 'AND orders.time >="'.(int)strtotime($date1).'" AND orders.time <="'.(int)strtotime($date2).'" ';

        }

        $res = mysql_query('SELECT orders.author, orders.order_id, orders.time, orders.order_number, orders.part_number, orders.laser_marking, orders.material, orders.color, orders.quantity, orders.demension, orders.date, orders.note, package.tool AS p_tool, package.package_number, package.package_id FROM package LEFT JOIN orders USING (package_id) WHERE package.package_id="'.$package.'" '.$where.' ORDER BY order_id DESC');

        if ($res) {

            while ($row = mysql_fetch_assoc($res)) {

                $data['orders'][] = array(

                    'id'            => $row['order_id'],
                    'author'        => $row['author'],
                    'order_number'  => $row['order_number'],
                    'part_number'   => $row['part_number'],
                    'laser_marking' => $row['laser_marking'],
                    'material'      => $row['material'],
                    'color'         => $row['color'],
                    'quantity'      => $row['quantity'],
                    'demension'     => $row['demension'],
                    'date'          => $row['date'],
                    'note'          => $row['note'],
                    'time'          => $this->getTime($row['time'])

                );

                $data['package'] = $row['package_number'];
                $data['package_id'] = $row['package_id'];
                $data['tool'] = $row['p_tool'];

            }

        } else {

            $data = false;

        }

        $this->db->close();

        return $data;

    }

    function get_order($id) {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM orders LEFT JOIN barcode USING (part_number) WHERE order_id=' . (int) $id);

        if ($res) {

            $data = mysql_fetch_assoc($res);

        } else {

            $data = false;

        }

        $this->db->close();

        return $data;

    }

    function scan_barcode($scaned) {

        // Если есть баркод то делаем выборк

        $this->db->connect();

        if ($scaned['barcode']) {

            $res = mysql_query('SELECT barcode.barcode, barcode.part_number, material.material, material.dm2 FROM barcode LEFT JOIN material USING (part_number) WHERE barcode="'.$scaned['barcode'].'"');

        }

        if ($scaned['part_number']) {

            $res = mysql_query('SELECT barcode.barcode, barcode.part_number, material.material, material.dm2 FROM barcode LEFT JOIN material USING (part_number) WHERE part_number="'.$scaned['part_number'].'"');

        }


        if ($res) {

            $cnt_row = mysql_num_rows($res);

            if ($cnt_row) {

                $data = mysql_fetch_assoc($res);

            } else {

                //mysql_query('INSERT INTO barcode (barcode) VALUES ("'.$scaned['barcode'].'")');

            }

        }

        $this->db->close();

        return $data;

    }

    function scan_part_number($scaned) {

        $this->db->connect();

        mysql_query('INSERT INTO barcode (barcode, part_number) VALUES ("'.$scaned['barcode'].'", "'.$scaned['part_number'].'")');

        $this->db->close();

    }

    function redaction_order($new) {

        $this->db->connect();

        if (mysql_query('UPDATE orders SET order_number="'.$new['order_number'].'", part_number="'.$new['part_number'].'", laser_marking="'.$new['laser_marking'].'", material="'.$new['material'].'", color="'.$new['color'].'", quantity="'.$new['quantity'].'", demension="'.$new['demension'].'", note="'.$new['note'].'" WHERE order_id='.$new['order_id'])) {

            $res = mysql_query('SELECT barcode FROM barcode WHERE part_number="'.$new['part_number'].'"');
            $row = mysql_fetch_assoc($res);

            if ($row['barcode'] == '') {

                mysql_query('UPDATE barcode SET barcode="'.$new['barcode'].'" WHERE part_number="'.$new['part_number'].'"');

            }

            $data = true;

        } else {

            $data = false;

        }

        $this->db->close();

        return $data;

    }

    function redaction_package($new) {

        $this->db->connect();

        if(mysql_query('UPDATE package SET package_number="'.$new['package_number'].'", date="'.$new['date'].'" WHERE package_id=' . $new['package_id'])) {

            $data = true;

        } else {

            $data = false;

        }

        $this->db->close();

        return $data;

    }

    /*function add_new_package($new) {

        $this->db->connect();

        $res = mysql_query('INSERT INTO orders (order_number, part_number, laser_marking, material, color, quantity, demension, date, note, tool) VALUES ("'.$new['order_number'].'", "'.$new['part_number'].'", "'.$new['laser_marking'].'", "'.$new['material'].'", "'.$new['color'].'", "'.$new['quantity'].'", "'.$new['demension'].'", "'.date('d.m.Y H:i:s').'", "'.$new['note'].'", "'.$new['tool'].'")');

        $scan = mysql_query('SELECT barcode FROM barcode WHERE barcode="'.$new['barcode'].'"');

        $cnt_rows = mysql_num_rows($scan);

        if (!$cnt_rows) {

            mysql_query('INSERT INTO barcode (part_number, barcode) VALUES ("'.$new['part_number'].'", "'.$new['barcode'].'")');
            mysql_query('INSERT INTO material (part_number, material, dm2) VALUES ("'.$new['part_number'].'", "'.$new['material'].'", "'.$new['demension'].'")');

        }

        if ($res) {

            $data = true;

        } else {

            $data = false;

        }

        $this->db->close();

        return $data;

    }*/

    function delete($id) {

        $this->db->connect();

        if (mysql_query('DELETE FROM orders WHERE order_id=' . (int) $id)) {

            $data = true;

        } else {

            $data = false;

        }

        $this->db->close();

        return $data;

    }

    function get_barcode($order) {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM barcode LEFT JOIN orders USING (part_number) WHERE orders.order_id='.$order['order_id'].' LIMIT 1');

        if ($res) {

            $data = mysql_fetch_assoc($res);

        } else {

            $data = 'No';

        }

        $this->db->close();

        return $data;

    }

    function get_package($id) {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM package WHERE package_id=' . (int) $id);

        if ($res) {

            $data = mysql_fetch_assoc($res);
            $data['time'] = $this->getTime($data['time']);

        } else {

            $data = 'No';

        }

        $this->db->close();

        return $data;

    }

    function get_order_info($order) {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM barcode LEFT JOIN orders USING (part_number) WHERE orders.order_id='.$order.' LIMIT 1');

        if ($res) {

            $data = mysql_fetch_assoc($res);

        } else {

            $data = 'No';

        }

        $this->db->close();

        return $data;

    }
    function get_package_info($id) {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM package  WHERE package_id='.$id.' LIMIT 1');

        if ($res) {

            $data = mysql_fetch_assoc($res);

        } else {

            $data = 'No';

        }

        $this->db->close();

        return $data;

    }
    function get_orders_data_info($id) {

        $this->db->connect();

        $res = mysql_query('SELECT * FROM orders_data  WHERE partno='.$id.' LIMIT 1');

        if ($res) {

            $data = mysql_fetch_assoc($res);

        } else {

            $data = 'No';

        }

        $this->db->close();

        return $data;

    }

    function searchTimeStamp()
    {
        $this->db->connect();

        $result = mysql_query('SELECT order_id, date, time FROM orders WHERE LENGTH(time) < 4');

        while ($row = mysql_fetch_assoc($result))
        {
            $p = explode('-', $row['date']);

            $timestamp = mktime($row['time'], 0, 0, $p[1], $p[2], $p[0]);

            //$data[$row['order_id']] = getdate($row['time']);

            mysql_query('UPDATE orders SET time='.$timestamp.' WHERE order_id='.$row['order_id']);

        }

        $thisTime = getdate(time());

        $searchTime = mktime(

            $thisTime['hours'] - $_GET['orderTime'],
            $thisTime['minutes'],
            $thisTime['seconds'],
            $thisTime['mon'],
            $thisTime['mday'],
            $thisTime['year']

        );

        $this->db->close();

        return $data;
    }

}

?>
