
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice&nbsp;<?= $invs->reference_no ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
    <style>
        .box{
            border: 1px solid black;
            border-radius: 7px;
            padding: 10px;
        }
        @media print {
            .box{

                padding: 10px!important;
            }
            .b1{
                height: 150px!important;
            }
        }
        body{

            font-family: "Khmer OS System","Times New Roman";
            -moz-font-family: "Khmer OS System";
        }
        .header1{
            font-family:"Khmer OS Muol Light";
            -moz-font-family: "Khmer OS System";

        }
        .tb_cus thead td{
            padding: 3px 0px;
        }
        .tfoot_ch td{
            padding: 3px 5px;
            text-align: right;
        }
        .tfoot td{
            width: calc(100%/4);
          padding-left:5px;
        }
        .tfoot td table{
            border: 1px solid black;
        }
        .tb_cus td{
            border-right: 1px solid black;
        }
    </style>
</head>
<body>
      <div class="container">
          <div class="header" >
              <table width="100%">
                  <tr>
                      <td style="vertical-align: bottom; ">
                          <h1><?= $biller->company ?></h1>
                          <div class="line" style="border: 1px solid black"></div>
                          <br>
                          <div class="box b1" style="height: 160px" >
                              <p>ឈ្មោះអតិថិជន / Customer Name : <?= $customer->name ?></p>
                              <p>តំណាង / Rep : <?= $customer->address ?></p>
                              <p>បញ្ជូនទៅ / Ship To : . . . . .</p>

                          </div>
                      </td>
                      <td width="3%"></td>
                      <td width="40%" style="vertical-align: bottom; ">

                          <h3 class="text-center header1">វិក័យប័ត្រ</h3>
                          <h3 class="text-center header1"><b>Invoice</b></h3><br>
                          <div class="box" >
                              <?php
//                                $this->erp->print_arrays($rows);
                              ?>
                              <p>កាលបរិច្ឆេត / Date : <?=  date("d/m/Y", strtotime($invs->date)); ?></p>
                              <p>ល.ខ​ វិក័យប័ត្រ / Inv No : <?= $invs->reference_no ?></p>
                              <p>ល.ខ ទូទាត់ / Terms: </p>
                          </div>
                      </td>
                  </tr>
              </table>
          </div>
          <br>
          <div class="body">
              <table border="1" width="100%" class="tb_cus">
                  <thead class="text-center"​ style="">
                    <td>ល.រ <br>No.</td>
                    <td>បរិយាយមុខទំនិញ<br>​Items Description</td>
                    <td>សម្គាល់<br>Remarks</td>
                    <td>បរិមាណ<br>QTY</td>
                    <td>ឯកតា<br>U/M</td>
                    <td>ថ្លៃឯកតា<br>Price</td>
                    <td>ថ្លៃទំនិញ<br>Amount</td>
                  </thead>
                  <tbody class="text-center">
                  <?php $r = 1;
                  $tax_summary = array();
                  foreach ($rows as $row):
                  $free = lang('free');
                  $product_unit = '';


                  if($row->variant){
                      $product_unit = $row->variant;
                  }else{
                      $product_unit = $row->uname;
                  }

                  $product_name_setting;
                  if($setting->show_code == 0) {
                      $product_name_setting = $row->product_name;
                  }else {
                      if($setting->separate_code == 0) {
                          $product_name_setting = $row->product_name;
                      }else {
                          $product_name_setting = $row->product_name;
                      }
                  }


                  ?>
                  <tr  style="border-bottom: transparent">
                      <td style="width:40px; "><?= $r; ?></td>

                      <td class="text-left" style="padding-left: 5px">
                          <?= $product_name_setting ?>
                          <?= $row->details ? '<br>' . $row->details : ''; ?>
                          <?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                      </td>
                      <td></td>
                      <td ><?php echo $product_unit ?></td>
                      <td><?= $this->erp->formatQuantity($row->quantity); ?></td>
                      <!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
                      <td ><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
                      <td><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
                  </tr>
                  <?php
                  $total += $row->subtotal;
                  $r++;
                  endforeach;


                    for($i=1;$i<(20-$r);$i++){
                        ?>
                        <tr style="border-bottom: transparent">
                            <td></td>
                            <td class="text-left" style="padding-left: 5px">&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                  ?>
                  </tbody>

                  <tfoot >
                        <tr style="border-top: 1px solid black">
                            <td rowspan="1" colspan="4"​ style="vertical-align: top; padding: 5px 18px">
                                <p>សម្គាល់ / Message : </p>
                                <div><?= $this->erp->decode_html($invs->note); ?></div>
                            </td>
                            <td colspan="3">
                                <table width="100%" class="tfoot_ch">
                                    <tr style="border-bottom: 1px solid black">
                                        <td style="border-right: 1px solid black">សរុប<br>Subtotal</td>
                                        <td style="border-right: none"><?= $this->erp->formatMoney($total); ?></td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black">
                                        <td width="50%" style="border-right: 1px solid black">ប្រាក់កក់<br>Deposite</td>
                                        <td width="50%" style="border-right: none"></td>
                                    </tr>
                                    <tr>
                                        <td style="border-right: 1px solid black">ប្រាក់នៅសល់<br>Balance</td>
                                        <td style="border-right: none"></td>
                                    </tr>
                                </table>
                            </td>

                        </tr>

                  </tfoot>
              </table>
          </div>
          <br>
          <div class="footer">
              <table class="tfoot" width="100%" >
                  <tr>
                      <td>

                          <table>
                              <tr  class="text-center">
                                  <td colspan="2"​ style="padding-top: 2px">អតិថិជន <br>Customer</td>
                              </tr>
                              <tr>
                                  <td>ឈ្មោះ ៖​ </td>
                                  <td> </td>
                              </tr>
                              <tr><td>Name </td><td></td></tr>
                              <tr>
                                  <td>ទូរស័ព្ទ​ ៖ </td><td></td>
                              </tr>
                              <tr><td>Phone</td><td></td></tr>
                              <tr>
                                  <td>ហត្ថលេខា​ ៖ </td>
                                  <td></td>
                              </tr>
                              <tr><td>Sign</td><td></td></tr>
                          </table>
                      </td>
                      <td>
                          <table>
                              <tr  class="text-center">
                                  <td colspan="2" style="padding-top: 2px">គណនេយ្យករ <br>Accountant Approved</td>
                              </tr>
                              <tr>
                                  <td>ឈ្មោះ ៖​ </td>
                                  <td> </td>
                              </tr>
                              <tr><td>Name </td><td></td></tr>
                              <tr>
                                  <td>ទូរស័ព្ទ​ ៖ </td><td></td>
                              </tr>
                              <tr><td>Phone</td><td></td></tr>
                              <tr>
                                  <td>ហត្ថលេខា​ ៖ </td>
                                  <td></td>
                              </tr>
                              <tr><td>Sign</td><td></td></tr>
                          </table>
                      </td>
                      <td>
                          <table>
                              <tr  class="text-center">
                                  <td colspan="2" style="padding-top: 2px">អ្នករៀបចំ<br>Prepare by</td>
                              </tr>
                              <tr>
                                  <td>ឈ្មោះ ៖​ </td>
                                  <td> </td>
                              </tr>
                              <tr><td>Name </td><td></td></tr>
                              <tr>
                                  <td>ទូរស័ព្ទ​ ៖ </td><td></td>
                              </tr>
                              <tr><td>Phone</td><td></td></tr>
                              <tr>
                                  <td>ហត្ថលេខា​ ៖ </td>
                                  <td></td>
                              </tr>
                              <tr><td>Sign</td><td></td></tr>
                          </table>
                      </td>
                      <td>
                          <table>
                              <tr  class="text-center">
                                  <td colspan="2" style="padding-top: 2px">តំណាងផ្នែកលក់ <br>Sale Rep</td>
                              </tr>
                              <tr>
                                  <td>ឈ្មោះ ៖​ </td>
                                  <td> </td>
                              </tr>
                              <tr><td>Name </td><td></td></tr>
                              <tr>
                                  <td>ទូរស័ព្ទ​ ៖ </td><td></td>
                              </tr>
                              <tr><td>Phone</td><td></td></tr>
                              <tr>
                                  <td>ហត្ថលេខា​ ៖ </td>
                                  <td></td>
                              </tr>
                              <tr><td>Sign</td><td></td></tr>
                          </table>
                      </td>
                  </tr>
              </table>
          </div>


      </div>

</body>
</html>
