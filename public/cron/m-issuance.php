<?php

error_reporting(1);
include 'config.php';

$date = "2018-04-09";
$end_date = "2018-07-01";
$token = sha1(md5("epivlmis#,0%$#communication" . date("Y-m-d")));

$level = array(0 => "district");

//$date = '2017-01-01';
// End date
//$end_date = date("Y-m-d");
while (strtotime($date) <= strtotime($end_date)) {

    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

    foreach ($level as $lev) {
        $url = "http://epimis.cres.pk/API/Communication/getIssuance?date=$date&token=$token&level=$lev";

        $ch = curl_init();
// Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
        curl_setopt($ch, CURLOPT_URL, $url);
// Execute
        $result = curl_exec($ch);
// Closing
        curl_close($ch);


        $dd = json_decode($result, true);

        $i = 0;

        // foreach ($dd['data'] as $key1 => $row1) {

        foreach ($dd['data'] as $key1 => $row1) {
            foreach($row1 as  $key => $row0){
//echo $key;
//echo "<PRE>";
//        print_r($row0);
//        echo "<PRE>";
            if (!empty($row0)) {
                $transactionid = $row0['transactionid']."<br>";
                $transactiondate = $row0['transactiondate'];
                $issuedby = $row0['issuedby'];
                $issuedbyid = $row0['issuedbyid'];
                $issuedto = $row0['issuedto'];
                $issuedtoid = $row0['issuedtoid'];
                $data_entry_month = $row0['batchnumber'];
                $expirydate = $row0['expirydate'];
                $productiondate = $row0['productiondate'];
                $itemid = $row0['itemid'];
                $itemname = $row0['itemname'];
                $vvmstage = $row0['vvmstage'];

                $issuedqty = $row0['issuedqty'] == NULL ? 0 : $row0['issuedqty'];

                if ($issuedqty > 0) {
                    $issuedqty = "-" . $issuedqty;
                }

                $qry_select2 = "SELECT
            item_pack_sizes.pk_id,
            item_mapping.epi_mis_batch_item_name,
            item_pack_sizes.item_unit_id,
            item_pack_sizes.item_category_id
            FROM
            item_mapping
            INNER JOIN item_pack_sizes ON item_pack_sizes.pk_id = item_mapping.item_pack_size_id
            WHERE
            item_mapping.epi_mis_item_id = $itemid";

                $row_select2 = $conn->query($qry_select2);

                $res_select2 = $row_select2->fetch_assoc();

                // item ID
                $item_id = $res_select2['pk_id'];
                $item_unit_id = $res_select2['item_unit_id'];
                $item_category_id = $res_select2['item_category_id'];
                $item_name_b = $res_select2['epi_mis_batch_item_name'];

                // from warehouse ID
                $str_qry_f_w = "SELECT
                warehouses.pk_id
                FROM
                warehouses
                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                WHERE
                locations.province_id = 3 AND
                locations.dhis_code = '$issuedbyid'";


                $row_f_w = $conn->query($str_qry_f_w);

                $res_f_w = $row_f_w->fetch_assoc();

                $from_warehouse_id = $res_f_w['pk_id'];

                // to warehouse ID
                $str_qry_t_w = "SELECT
                warehouses.pk_id
                FROM
                warehouses
                INNER JOIN locations ON warehouses.location_id = locations.pk_id
                WHERE
                locations.province_id = 3 AND
                locations.dhis_code = '$issuedtoid'";


                $row_t_w = $conn->query($str_qry_t_w);

                $res_t_w = $row_t_w->fetch_assoc();

                $to_warehouse_id = $res_t_w['pk_id'];



                $qry_select = "SELECT
            stock_master.pk_id
            
            FROM
            stock_master
            
            WHERE
            stock_master.transaction_type_id = 2 AND 
            stock_master.to_warehouse_id = '$to_warehouse_id'"
                        . " AND stock_master.from_warehouse_id = '$from_warehouse_id'"
                        . " AND DATE_FORMAT(stock_master.transaction_date,'%Y-%m-%d') = '$transactiondate'";


                $row_select = $conn->query($qry_select);

                $res_select = $row_select->fetch_assoc();

                if (empty($res_select)) {
                    $tr_type_e = 2;
                    $tr_date_e = $transactiondate;
                    $wh_id_e = $from_warehouse_id;
                    $trans_id_e = '';
                    $trans = getTransactionNumber($tr_type_e, $tr_date_e, $wh_id_e, $trans_id_e);

                    $tr_number = $trans['trans_no'];
                    $tr_counter = $trans['id'];
                    $str_qry1 = "INSERT INTO stock_master
                    (stock_master.transaction_date,
                    stock_master.transaction_number,
                    stock_master.transaction_counter,
                    stock_master.draft,
                    stock_master.transaction_type_id,
                    stock_master.from_warehouse_id,
                    stock_master.to_warehouse_id,
                    stock_master.stakeholder_activity_id,
                    stock_master.parent_id ,
                    stock_master.created_by,
                    stock_master.created_date,
                    stock_master.modified_by,
                    stock_master.modified_date
                    )
                    VALUES ('$transactiondate', '$tr_number','$tr_counter','0','2','$from_warehouse_id','$to_warehouse_id','1','0','1',NOW(),'1',NOW())";

                    $conn->query($str_qry1);
                    $stock_master_id = $conn->insert_id;
                } else {
                    $stock_master_id = $res_select['pk_id'];
                }
                $qry_select_pack = "SELECT
                pack_info.pk_id,
                pack_info.stakeholder_item_pack_size_id
                FROM
                pack_info
                INNER JOIN stakeholder_item_pack_sizes ON pack_info.stakeholder_item_pack_size_id = stakeholder_item_pack_sizes.pk_id
                INNER JOIN item_pack_sizes ON stakeholder_item_pack_sizes.item_pack_size_id = item_pack_sizes.pk_id
                WHERE
                item_pack_sizes.pk_id = '$item_id'
                and stakeholder_item_pack_sizes.stakeholder_id = 93
                LIMIT 1";

                $row_select_pack = $conn->query($qry_select_pack);

                $res_select_pack = $row_select_pack->fetch_assoc();

                $pack_info_id = $res_select_pack['pk_id'];

                $qry_batch_sel = "SELECT
                stock_batch.pk_id,
                stock_batch.number,
                stock_batch.expiry_date,
                stock_batch.unit_price,
                stock_batch.production_date,
                stock_batch.vvm_type_id,
                stock_batch.pack_info_id,
                stock_batch.created_by,
                stock_batch.created_date,
                stock_batch.modified_by,
                stock_batch.modified_date
                FROM
                stock_batch
                WHERE
                stock_batch.number = 'EPIMIS-$item_name_b' AND
                stock_batch.pack_info_id = '$pack_info_id'";
                $row_select_batch = $conn->query($qry_batch_sel);

                $res_select_batch = $row_select_batch->fetch_assoc();

                if (empty($res_select_batch)) {
                    $expiry_date = date('Y-m-d', strtotime('+5 years'));
                    $production_date = date('Y-m-d');
                    $str_qry3 = "INSERT INTO stock_batch
                    (stock_batch.number,
                    stock_batch.expiry_date,
                    stock_batch.production_date,
                    stock_batch.unit_price,
                    stock_batch.vvm_type_id,
                    stock_batch.pack_info_id,
                    stock_batch.created_by,
                    stock_batch.created_date,
                    stock_batch.modified_by,
                    stock_batch.modified_date
                    )
                    VALUES ('EPIMIS-$item_name_b','$expiry_date','$production_date','0','1', '$pack_info_id','1',NOW(),'1',NOW())";

                    $conn->query($str_qry3);
                    $batchid = $conn->insert_id;
                } else {
                    $batchid = $res_select_batch['pk_id'];
                }
                $qry_batch_warehouse_sel = "SELECT
                    stock_batch_warehouses.pk_id,
                    stock_batch_warehouses.quantity
                    FROM
                    stock_batch_warehouses
                    INNER JOIN stock_batch ON stock_batch_warehouses.stock_batch_id = stock_batch.pk_id
                    WHERE
                    stock_batch.pk_id = '$batchid' AND
                    stock_batch_warehouses.warehouse_id = '$from_warehouse_id' AND
                    stock_batch.pack_info_id = '$pack_info_id'";
                $row_select_batch_warehouse = $conn->query($qry_batch_warehouse_sel);

                $res_select_batch_warehouse = $row_select_batch_warehouse->fetch_assoc();
                
                    if (empty($res_select_batch_warehouse)) {
                        $str_qry5 = "INSERT INTO stock_batch_warehouses
                       (stock_batch_warehouses.quantity,
                        stock_batch_warehouses.`status`,
                        stock_batch_warehouses.warehouse_id,
                        stock_batch_warehouses.stock_batch_id,
                        stock_batch_warehouses.created_by,
                        stock_batch_warehouses.created_date,
                        stock_batch_warehouses.modified_by,
                        stock_batch_warehouses.modified_date
                    )
                    VALUES ('$issuedqty', 'Running','$from_warehouse_id','$batchid','1',NOW(),'1',NOW())";

                        $conn->query($str_qry5);
                        $stock_batch_id = $conn->insert_id;
                    } else {
                        $stock_batch_id = $res_select_batch_warehouse['pk_id'];


                        $str_qry1 = "SELECT AdjustQty($stock_batch_id,$from_warehouse_id) from DUAL";
                        $conn->query($str_qry1);
                    }
                    $str_qry2_detail = "INSERT INTO stock_detail
                    (stock_detail.quantity,
                    stock_detail.`temporary`,
                    stock_detail.vvm_stage,
                    stock_detail.adjustment_type,
                    stock_detail.stock_master_id,
                    stock_detail.stock_batch_warehouse_id,
                    stock_detail.item_unit_id,
                    stock_detail.created_by,
                    stock_detail.created_date,
                    stock_detail.modified_by,
                    stock_detail.modified_date
                    )
                    VALUES ('$issuedqty', '0', '$vvmstage','2','$stock_master_id','$stock_batch_id','$item_unit_id','1',NOW(),'1',NOW())";

                    $conn->query($str_qry2_detail);
                    $stock_detail_id_last = $conn->insert_id;
                

                if ($item_category_id == 1) {

                    $qry_placement_location = "SELECT DISTINCT
                    warehouses.warehouse_name,
                    cold_chain.asset_id,
                    placement_locations.pk_id as location_id,
                    warehouses.pk_id
                    FROM
                    cold_chain
                    INNER JOIN warehouses ON cold_chain.warehouse_id = warehouses.pk_id
                    INNER JOIN placement_locations ON cold_chain.pk_id = placement_locations.location_id
                    WHERE
                    warehouses.province_id = 3 AND
                    warehouses.stakeholder_office_id = 2 AND
                    placement_locations.location_type = 99
                    and warehouses.pk_id = '$from_warehouse_id'
                    LIMIT 1";
                    $row_placement_location = $conn->query($qry_placement_location);

                    $res_placement_location = $row_placement_location->fetch_assoc();
                    $placement_location_id = $res_placement_location['location_id'];
                }
                // Non Vaccines
                else {
                    $qry_placement_location = "SELECT DISTINCT
                        non_ccm_locations.location_name,
                        non_ccm_locations.warehouse_id,
                        warehouses.warehouse_name,
                        placement_locations.pk_id as location_id
                        FROM
                        non_ccm_locations
                        INNER JOIN warehouses ON non_ccm_locations.warehouse_id = warehouses.pk_id
                        INNER JOIN placement_locations ON non_ccm_locations.pk_id = placement_locations.location_id
                        WHERE
                        warehouses.province_id = 3 AND
                        warehouses.stakeholder_office_id = 2 AND
                        placement_locations.location_type = 100
                        and warehouses.pk_id = '$from_warehouse_id'
                    LIMIT 1";
                    $row_placement_location = $conn->query($qry_placement_location);

                    $res_placement_location = $row_placement_location->fetch_assoc();
                    $placement_location_id = $res_placement_location['location_id'];
                }

                $str_qry2_placment = "INSERT INTO placements
                    (placements.quantity,
                    placements.vvm_stage,
                    placements.is_placed,
                    placements.placement_location_id,
                    placements.stock_batch_warehouse_id,
                    placements.stock_detail_id,
                    placements.placement_transaction_type_id,
                    placements.created_by,
                    placements.created_date,
                    placements.modified_by,
                    placements.modified_date
                    )
                    VALUES ('$issuedqty','$vvmstage','1','$placement_location_id','$stock_batch_id','$stock_detail_id_last','115','1',NOW(),'1',NOW())";

                $conn->query($str_qry2_placment);
                // 'qry NO: ' . $i . '--' . $str_qry2_placment . "<br>";
                $i++;
            }
        
    }
    }
    }
}

function getTransactionNumber($tr_type, $tr_date, $wh_id = null, $trans_id = null) {

    //$time_arr = explode(' ', $tr_date);

    $current_date = explode("-", $tr_date);

    $current_month = $current_date[1];
    $current_year = $current_date[0];

    $from_date = $current_year . "-" . $current_month . "-01";
    $to_date = $current_year . "-" . $current_month . "-31";

    if ($trans_id > 0) {
        $last_id = getLastIDExceptMe($from_date, $to_date, $tr_type, $trans_id, $wh_id);
    } else {
        $last_id = getLastID($from_date, $to_date, $tr_type, $wh_id);
    }

    if ($last_id == NULL) {
        $last_id = 0;
    }

    $last_id += 1;

    if ($tr_type == 1) {
        return array(
            "id" => $last_id,
            "trans_no" => "R" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
        );
    }
    if ($tr_type == 2) {
        return array(
            "id" => $last_id,
            "trans_no" => "I" . substr($current_year, -2) . $current_month . str_pad(($last_id), 4, "0", STR_PAD_LEFT)
        );
    }
}

function getLastIDExceptMe($from_date, $to_date, $tr_type, $trans_id, $wh_id) {
    include 'config.php';
    if ($tr_type == '1') {
        $f = "stock_master.to_warehouse_id = '$wh_id'";
    } else {
        $f = "stock_master.from_warehouse_id = '$wh_id'";
    }
    $st = "SELECT
        MAX(stock_master.transaction_counter) as Maxtr
        FROM
        stock_master
        WHERE
        stock_master.transaction_type_id = $tr_type AND
        $f AND
        stock_master.pk_id <> '$trans_id' AND
        DATE_FORMAT(stock_master.transaction_date,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date'  ";

    $row1 = $conn->query($st);

    $row = $row1->fetch_assoc();
    return $row['Maxtr'];
}

function getLastID($from_date, $to_date, $tr_type, $wh_id) {
    include 'config.php';
    if ($tr_type > 2) {
        $var1 = "stock_master.transaction_type_id > 2 ";
    } else {
        $var1 = "stock_master.transaction_type_id =  $tr_type ";
    }

    if ($tr_type == 1) {
        $var2 = "stock_master.to_warehouse_id = $wh_id ";
    } else {
        $var2 = "stock_master.from_warehouse_id =  $wh_id";
    }

    $st = "SELECT
        MAX(stock_master.transaction_counter) as Maxtr
        FROM
        stock_master
        WHERE
        
        DATE_FORMAT(stock_master.transaction_date,'%Y-%m-%d') BETWEEN '$from_date' AND '$to_date' AND $var1 AND $var2 ";

    $row1 = $conn->query($st);

    $row = $row1->fetch_assoc();
    return $row['Maxtr'];
}
