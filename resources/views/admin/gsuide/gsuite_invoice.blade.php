
<!DOCTYPE html>
<html class="no-js" lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <title>Webbitech CRM Invoice</title>
  <link rel="stylesheet" href="<?php echo url('');?>/public/admin_assets/invoice/style.css">
</head>
<?php 

$invoice_id = $domainhosting->id;
$customer_name = $domainhosting->customers_list->fld_name;
$customer_email = $domainhosting->customers_list->fld_email;
$customer_phone = $domainhosting->customers_list->fld_phone;
$customer_address = $domainhosting->customers_list->fld_address;
$company_name = $domainhosting->customers_list->fld_company_name;
$customer_gstno = $domainhosting->customers_list->fld_customer_gstno;

$tax_percentage_get = $domainhosting->fld_tax_percentage;
$amount = $domainhosting->fld_amount;
$email_cnt = $domainhosting->fld_email_count;
$total_amount = $domainhosting->fld_total_amount;
$description = $domainhosting->fld_description;

$gsuite_id = $domainhosting->id;
$domain_name = $domainhosting->fld_domain_name;
$gsuite_start_date = $domainhosting->fld_gsuite_start_date;
$gsuite_tenure = $domainhosting->fld_gsuite_tenure;
$gsuite_end_date = $domainhosting->fld_gsuite_end_date;

$tax_percentage = ($tax_percentage_get == "") ? 0 : $tax_percentage_get; 
$tax_calc = $tax_percentage * 0.01;
$tax_amount = ($amount * $tax_calc);
$tax_amount_calc = $tax_amount !="" || $tax_amount !=0 ? $tax_amount : 0;
$all_total_amount = $amount + $tax_amount_calc;

$list_email = [];
$email_list = $domainhosting->email_list;
foreach ($email_list as $key => $value) {
  $list_email[] = $value->fld_email;
}

$num_row_gs_email = $list_email;

?>

<body>
  <div class="cs-container" id="invoice_data">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">
        <div class="text-center text-sm-end notpaidtitle">
          <h4 class="mb-0"> <span class="notpaid">Not Paid</span> </h4>
        </div>
        <div class="cs-invoice_head cs-type1 cs-mb25 row_reverese">
          <div class="cs-invoice_left">
            <p class="cs-invoice_number cs-primary_color cs-mb5 cs-f16"><b class="cs-primary_color">Proforma Invoice</p>
            
          </div>
          <div class="cs-invoice_right cs-text_right">
            <div class="cs-logo cs-mb5"><img src="<?php echo url('');?>/public/admin_assets/images/logo.png" alt="Logo" style="width: 200px;"></div>
          </div>
        </div>
        <div class="cs-invoice_head cs-mb10">
          <div class="cs-invoice_left">
            <b class="cs-primary_color">Invoice To:</b>
            <p>             
              <span style="display: none;" id="customer_name">Gsuide Invoice</span>
              <span><?php echo $company_name; ?></span><br>
              <?php echo $customer_name; ?> <br>
              <?php echo $customer_phone; ?> <br>
              <?php echo $customer_address; ?><br>             
              <?php echo $customer_gstno; ?><br><br>
              <span>Invoice No : <?php echo "INV00".$invoice_id; ?></span><br>
              <span id="invoice_date" inv_dt="<?php echo date("d-m-Y"); ?>">Invoice Date : <?php echo date("d-m-Y"); ?></span><br> 
              <span>Due Date : <?php echo date('d-m-Y', strtotime($gsuite_end_date)); ?></span>
            </p>
          </div>
          <div class="cs-invoice_right cs-text_right">
            <b class="cs-primary_color">Pay To:</b>
            <p>
              Webbitech<br>
              13/1b,Brooke Bond Layout,<BR> Krishnasamy Mudaliar Road,<BR>RS Puram, Coimbatore-641002<br>
              info@webbitech.com | www.webbitech.com<br>
              GST : 33ATQPR5217B1ZR | PAN : ATQPR5217B1ZR<br>
              Mob. 9789239293 | 9901526822<br>
           
            </p>
          </div>
        </div>
        <div class="cs-table cs-style1">
          <div class="cs-round_border">
            <div class="cs-table_responsive">
              <table>
                <thead>
                  <tr>
                    <th class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Item</th>
                    <th class="cs-width_4 cs-semi_bold cs-primary_color cs-focus_bg">Description</th>
                    <th class="cs-width_1 cs-semi_bold cs-primary_color cs-focus_bg">Qty</th>
                    <th class="cs-width_2 cs-semi_bold cs-primary_color cs-focus_bg">Price</th>
                    <th class="cs-width_2 cs-semi_bold cs-primary_color cs-focus_bg cs-text_right">Total</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                  if($domain_name !="")
                  {
                ?>
                  <tr>
                    <td class="cs-width_3">Gsuite Renewals</td>
                    <td class="cs-width_4"><?php echo $domain_name;?></td>
                    <td class="cs-width_1"><?php echo $email_cnt;?></td>
                    <td class="cs-width_2">&#x20b9; <?php echo $amount; ?></td>
                    <td class="cs-width_2 cs-text_right">&#x20b9; <?php echo $amount; ?></td>
                  </tr>
                <?php 
                  }
                ?>
                 
                </tbody>
              </table>
            </div>
            <div class="cs-invoice_footer cs-border_top">
              <div class="cs-left_footer cs-mobile_hide">
                <p class="cs-mb0"><b class="cs-primary_color">Additional Information:</b></p>
                <p class="cs-m0">At check in you may need to present the credit <br>card used for payment of this ticket.</p>
              </div>
              <div class="cs-right_footer">
                <table>
                  <tbody>
                    <tr class="cs-border_left">
                      <td class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Subtoal</td>
                      <td class="cs-width_3 cs-semi_bold cs-focus_bg cs-primary_color cs-text_right">&#x20b9; <?php echo $amount; ?></td>
                    </tr>
                    <tr class="cs-border_left">
                      <td class="cs-width_3 cs-semi_bold cs-primary_color cs-focus_bg">Tax <?php if($tax_percentage!="" && $tax_percentage !=0)
                      {
                        echo "(".$tax_percentage."%)";
                      } 
                      ?>
                      </td>
                      <td class="cs-width_3 cs-semi_bold cs-focus_bg cs-primary_color cs-text_right"> + &#x20b9;<?php echo $tax_amount_calc; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="cs-invoice_footer">
            <div class="cs-left_footer cs-mobile_hide"></div>
            <div class="cs-right_footer">
              <table>
                <tbody>
                  <tr class="cs-border_none">
                    <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color">Total Amount</td>
                    <td class="cs-width_3 cs-border_top_0 cs-bold cs-f16 cs-primary_color cs-text_right">&#x20b9;<?php echo $all_total_amount; ?> </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @if($domainhosting->fld_description)
        <div class="cs-invoice_head cs-mb10">

          <div class="cs-invoice_left">
            <b class="cs-primary_color">Description</b>
            <p>
                {{ $domainhosting->fld_description }}
            </p>
          </div>
          
        </div>
        @endif
        <div class="cs-invoice_head cs-mb10">
          <div class="cs-invoice_left">
            <b class="cs-primary_color">Bank Details</b>
            <p>
              <span>Bank Name : BANK OF BARODA</span><br>
              Branch : SAIBABA COLONY<br>
              Account Name : WEBBITECH<br>
              Account Number : 26210200000897<br>            
              Ifsc Code : BARB0SAICOI<br>
            </p>
          </div>
          
        </div>
        <div class="cs-note">
        
          <div class="cs-note_right">
         
            <p class="cs-m0 text-center"><b class="cs-primary_color cs-bold">Note:</b>This is computer generated receipt and does not require physical signature</p>
          </div>
        </div><!-- .cs-note -->
      </div>
      <div class="cs-invoice_btns cs-hide_print">
        <a  onclick="javascript:history.back();" class="cs-invoice_btn cs-color1">
          <span>Back</span>
        </a>
        <button id="download_btn" class="cs-invoice_btn cs-color2">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Download</title><path d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M176 272l80 80 80-80M256 48v288"/></svg>
          <span>Download</span>
        </button>
      </div>
    </div>
  </div>
  <script src="<?php echo url('');?>/public/admin_assets/invoice/jquery.min.js"></script>
  <script src="<?php echo url('');?>/public/admin_assets/invoice/jspdf.min.js"></script>
  <script src="<?php echo url('');?>/public/admin_assets/invoice/html2canvas.min.js"></script>
  <script src="<?php echo url('');?>/public/admin_assets/invoice/main.js"></script>
</body>
</html>