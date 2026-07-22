<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Webbitech Project Report</title>
     
    <style>
            body{
                font-family: "Roboto", sans-serif;
            }
        p{
                margin-bottom:0px;
                padding-bottom:0px
            }
      table{
                border-spacing: 0;
            }
            table td, table th, p , span, strong{
                font-size: 12px !important;
                line-height: 20px;
                vertical-align:top;
            }
           
            .client_address p
            {
                line-height: 10px;
            }
            
            .client_address p:nth-child(2)
            {
                margin-top: 0px;
            }

            .table-no-border{
                width: 100%;
            }
            .table-no-border .width-50{
                width: 50%;
            }
            .table-no-border .width-70{
                width: 70%;
                text-align: left;
            }
            .table-no-border .width-30{
                width: 30%;
            }
            .table-no-border .width-35{
                width: 35%;
            }
            .table-no-border .width-65{
                width: 65%;
            }
            .margin-top{
                margin-top: 20px;
            }
            .product-table{
                margin-top: 20px;
                width: 100%;
                border: 1px solid #ddd;
                border-right:none;
                  vertical-align: text-top;
            }
            .product-table thead{
                    border-radius:5px 5px 0px 0px;
            }
            .product-table thead th{
                background-color: #00AFF0;
                color: white;
                padding: 5px;
                text-align: left;
                padding: 5px 10px;

            }
            .width-3{
                width: 3%;
            }
            .width-5{
                width: 5%;
            }
            .width-10{
                width: 10%;
            }
            .width-20{
                width: 20%;
            }
            .width-30{
                width: 30%;
            }
            .width-50{
                width: 50%;
            }
            .width-25{
                width: 25%;
            }
            .width-70{
                width: 70%;
                text-align: right;
            }
            .width-60{
                width: 60%;
                
            }
            .width-40{
                width: 40%;
                
            }
            .product-table tbody td{
                background-color: #fff;
                color: black;
                padding: 5px 10px;
              

            }
            .product-table td:last-child, .product-table th:last-child{
                text-align: right;
            }
            .product-table tfoot td{
                color: black;
                padding: 5px 15px;
            }
            .footer-div{
                background-color: #F1F5F9;
                margin-top: 100px;
                padding: 3px 10px;
            }
            .new1 {
            border: 1px dashed #222;
            margin: 20px 0 0 0;

            }
            .fnt-we-300{
                font-weight: 300 !important;
            }
            .fnt-we-400{
                font-weight: 400 !important;
            }
            .fnt-we-500{
                font-weight: 500 !important;
            }
            .fnt-we-600{
                font-weight: 600 !important;
            }
            .fnt-16{
                font-size: 16px !important;
            }
            .fnt-17{
                font-size: 17px !important;
            }
            .fnt-18{
                font-size: 18px !important;
            }
            .marg-both-10{
margin: 10px 0 !important;
            }
            .marg-both-20{
margin: 20px 0 !important;
            }
            .marg-both-30{
margin: 30px 0 !important;
            }
            .marg-both-40{
margin: 40px 0 !important;
            }
                .sales-invoice {
            background: #111111;
            padding: 7px 10px;
            font-size: 14px;
            color: #fff;
            border-radius: 8px;
            display: inline-block;
        }
        p{
            font-size: 14px;
        }
        td
        {
          border: 1px solid #e2e2e2;
        }
    </style>
  </head>

  <body>
  <?php
      $page_user_tile = "Project List - ".$user_title_name;
  ?>
    <table class="table-no-border">
        <tr>
            <td class="width-60" style="border:none">
            
            <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('admin_assets/images/logo.png')))}}" alt="Logo" style="width: 200px;">
            </td>
            <td class="width-30" style="text-align: right; border: none;">
                {{ $page_user_tile }}
                <br>
                @if(isset($start_date) && $start_date)
                  {{ date('d-m-Y', strtotime($start_date)) }} - {{ date('d-m-Y', strtotime($end_date)) }}
                @endif
            </td>
        </tr>
    </table>
  
  <div>
      
    <table class="product-table">
        <thead>
            <tr>
                <th class="width-3" style="border-bottom: 1px solid #00AFF0;text-align: center;">
                    <strong>S.No</strong>
                </th>
                 <th class="width-25" style="border-bottom: 1px solid #00AFF0;text-align: center;">
                    <strong>Project</strong>
                </th>

                 <th class="width-25" style="border-bottom: 1px solid #00AFF0;text-align: center;">
                    <strong>Sales User </strong>
                </th>
                
                <th class="width-25" style="border-bottom: 1px solid #00AFF0;text-align: center;">
                    <strong>Total Amount</strong>
                </th>
                <th class="width-5" style="border-bottom: 1px solid #00AFF0;text-align: center;">
                    <strong>Pending</strong>
                </th>
                 <th class="width-25" style="border-bottom: 1px solid #00AFF0;text-align: center;">
                    <strong>Confirm Date</strong>
                </th>
            </tr>
        </thead>
        <?php
            $total_amount1 = 0;
            $total_pening = 0;
        ?>
        <tbody>

            @if($users)
              @foreach($users as $key => $value)
              <?php
              // dd($value->added_user_details);
                  $total_amount = 0;
                  $total_penc = 0;
                  $bit_amounts = $value->bit_amounts;
                  if (count($bit_amounts) > 0) {
                      foreach ($bit_amounts as $key1 => $value1) {
                          $total_amount += $value1->fld_project_amount;
                      }
                  }
                  $total_penc = $value->bid_amount - $total_amount;
                  $total_amount1 += $value->bid_amount;
                  $total_pening += $total_penc;
              ?>
              <tr @if($value->status == 6) style="background: #dc3545;" @endif style="text-align: center;">
                  <td>{{ $key + 1}}</td>
                  <td>{{ $value->name }}</td>
                  <td>{{ $value->added_user_details->name ?? '' }} </td>
                  <td>{{ $value->bid_amount }}</td>
                  <td>
                      {{ $total_penc }}
                  </td>
                  <td>
                      @if($value->sales_user_date == "0000-00-00")
                      @else
                          {{ date('d-m-Y', strtotime($value->sales_user_date)) }}
                      @endif
                  </td>
              </tr>
              @endforeach
              @endif
  
        </tbody>
        <tfoot>
            <tr style="text-align: center;">
                <td></td>
                <td></td>
                <td></td>
                <td>
                    {{ $total_amount1 }}
                </td>
                <td>
                    {{ $total_pening }}
                </td>
                <td></td>
            </tr>
        </tfoot>
       
    </table>

    </div>

    </div>

  </body>
</html>