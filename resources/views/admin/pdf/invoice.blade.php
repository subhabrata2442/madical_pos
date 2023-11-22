<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>CASH MEMO</title>
  <style>
    * {
      padding: 0;
      margin: 0;
    }

    @media print {
      @page {
        margin: 0 auto;
        sheet-size: 300px 250mm;
      }

      html {
        direction: ltr;
      }

      html,
      body {
        margin: 0;
        padding: 0
      }

      #printContainer {
        width: 300px;
        margin: auto;
        text-align: justify;
      }

      .text-center {
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <table border="0" cellspacing="0" align="center" cellpadding="0" style="font-size:12px; color:#000;margin:0 auto;padding: 0 15px;">
    <tr>
      <td colspan="4" style="padding:2px 3px;font-weight:bold;text-align: center;">
        Sales Invoice</td>
    </tr>
    <tr>
      <td colspan="4"
        style="padding:7px 3px;text-align: center; border-top: #000 1px solid; border-bottom: #000 1px solid; font-size: 18px; font-weight: bold;">
        {{$shop_details['name']}}</td>
    </tr>
    <tr>
      <td colspan="4" style="padding:6px 3px; text-align: center;  border-bottom: #000 1px solid">
        {{$shop_details['address1']}}<br />
        Ph. {{$shop_details['phone']}}<br />
        E Mail: {{$shop_details['email']}}
        </td>
    </tr>
    <tr>
      <td colspan="2" style="padding:6px 3px; text-align: left;">Bill No.: {{$invoice_details['bill_no']}}</td>
      <td colspan="2" style="padding:6px 3px; text-align: left;">Date: {{$invoice_details['invoice_date']}}<br />
        Time: {{$invoice_details['invoice_time']}}</td>
    </tr>
    <tr>
      <td colspan="4"
        style="padding:6px 3px; text-align:left; border-top:#000 1px solid;  border-bottom: #000 1px solid">
        To, {{$customerdetails['name']}}
        <br />
        Ph: {{$customerdetails['phone']}}
      </td>
    </tr>
    <tr>
      <td style="padding:6px 3px; text-align: center;font-weight:bold; font-size: 16px; border-bottom: #000 1px solid;">
        Description</td>
      <td style="padding:6px 3px; text-align: center;font-weight:bold; font-size: 16px; border-bottom: #000 1px solid;">
        Qty</td>
      <td style="padding:6px 3px; text-align: center;font-weight:bold; font-size: 16px; border-bottom: #000 1px solid;">
        Rate</td>
      <td style="padding:6px 3px; text-align: center;font-weight:bold; font-size: 16px; border-bottom: #000 1px solid;">
        Amount</td>
    </tr>
    @if (count($items) > 0)
      @forelse ($items as $item)
        <tr>
          <td style="padding:6px 3px; text-align: center;">
            {{$item['product_name']}}</td>
          <td style="padding:6px 3px; text-align: center;">
            {{$item['qty']}}</td>
          <td style="padding:6px 3px; text-align: center;">
            {{$item['mrp']}}</td>
          <td style="padding:6px 3px; text-align: center;">
            {{$item['final_price']}}</td>
        </tr>
      @empty
      @endforelse
    @endif
    <tr>
      <td colspan="2" style="padding:9px 3px 3px; text-align: left; border-top: #000 1px solid;border-bottom: #000 1px solid;">Total Qty</span>
      </td>
      <td colspan="2" style="padding:9px 3px 3px; text-align: left; border-top: #000 1px solid;border-bottom: #000 1px solid;">{{$total['total_qty']}}</td>
    </tr>
    {{-- <tr>
      <td colspan="2" style="padding:6px 3px 9px; text-align: left;border-bottom: #000 1px solid; ">Item Discount
        Inclusive</span>
      </td>
      <td colspan="2" style="padding:6px 3px 9px; text-align: left;border-bottom: #000 1px solid;">571.43</td>
    </tr> --}}
    <!--<tr>
    <td colspan="4" style="padding:6px 3px 20px; text-align: left;">GST NO: <span>25ADSX199D2</span></td>
  </tr>-->
    <tr>
      <td colspan="3" style="padding:6px 3px 3px; text-align: left;">Gross Amount
      </td>
      <td style="padding:6px 3px 3px; text-align:right;">{{$total['gross_amount']}}</td>
    </tr>
    <tr>
      <td colspan="3" style="padding:3px 3px 3px; text-align: left;">Bill Discount
      </td>
      <td style="padding:3px 3px 3px; text-align:right;">{{$total['total_disc']}}</td>
    </tr>
    <tr>
      <td colspan="3" style="padding:6px 3px 3px; text-align: left;">Charge
      </td>
      <td style="padding:6px 3px 3px; text-align:right;">{{$total['total_charge']}}</td>
    </tr>
    


    <tr>
      <td colspan="3" style="padding:3px 3px 6px;font-weight:bold;text-align: left;border-bottom: #000 1px solid;">Net
        Amount</td>
      <td style="padding:3px 3px 6px; text-align:right; border-bottom: #000 1px solid;">{{$total['total_price']}}</td>
    </tr>
    <tr>
      <td colspan="4" style="padding:6px 3px 3px; text-align: center;">{{$total_amt_in_word}}</td>
    </tr>
    <tr>
      <td colspan="4" style="padding:3px 3px 6px; text-align: center; font-size: 18px; font-weight: bold;">Tender
        Details
      </td>
    </tr>
    <tr>
      <td colspan="2" style="padding:3px 3px; ">Advance Amount</td>
      <td colspan="2" style="padding:3px 3px; text-align: right;">{{$tender['tendered_amount']}}</td>
    </tr>
    <tr>
      <td colspan="2" style="padding:3px 3px;">Balance Amount</td>
      <td colspan="2" style="padding:3px 3px; text-align: right;">{{$tender['tendered_change_amount']}}</td>
    </tr>
    <tr>
      <td colspan="4" style="padding:6px 3px; text-align: center;border-bottom:#000 1px solid">Cash: {{$tender['tendered_amount']}}, Credit
        Card: 0.00, Card Bank 0.00
        Card<br />Bank Wallet: 0.00 Card Bank Chq. 0.00 Card Bank</td>
    </tr>
    <tr>
      <td colspan="4" style="padding:10px 3px 3px;font-weight:bold;text-align: center; font-size: 16px;">Have a Nice Day
      </td>
    </tr>
    <tr>
      <td colspan="4" style="padding:5px 3px;font-weight:bold;text-align: center;">*Thank You. Visit Again. Your
        satisfaction is our moto*</td>
    </tr>
  </table>
</body>

</html>