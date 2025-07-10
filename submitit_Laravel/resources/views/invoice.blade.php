<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Submitit</title>
<style>
    .table-size-main{
        width:94%;
        margin:0 auto;
        display:block;
        padding-top:10px;
    }
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #495057;
        vertical-align: top;
        border: 1px solid #eef3f7;
        font-family: 'Nunito', sans-serif!important;
        border-collapse: collapse;
        margin-top:20px;
    }
    table.table thead th, 
    table.table tbody td,
    table.table tfoot td,
    div,h1,h2,h4,h3,h5,h6,span,input,button,a {
        font-family: 'Poppins', sans-serif!important;
    }
    table.table thead th, 
    table.table tbody td {
        padding: 10px;
    }
    table.table tfoot{
        padding-top: 20px;
    }
    table.table tfoot td{
        padding: 5px 10px;
    }
    .table-bordered td, 
    .table-bordered th {
        border: 2px solid #fff;
    }
    .table th {
        font-weight: 700;
        font-size:15px;
        text-align: left;
    }
    .table td {
        font-size:14px;
    }
    .logoStyle {
        float: left;
        width: 60%;
    }
    .sideHeader {
        float: right;
        width: auto;
        padding-right: 10%;
    }
    .logoStyle img {
        max-width: 400px;
    }
    .topHeader:before, .topHeader:after {
        display: table;
        content: '';
        clear: both;
    }
    .logoStyle h1{
        font-size:40px;
        font-weight:bold;
        padding-bottom:30px;
    }
    .list-top-header ul{
        list-style:none;
        padding-left:0;
        margin:0;
    }
    .list-top-header li {
        color: #495057;
        padding-bottom: 5px;
        font-size: 14px;
        font-weight: 500;
    }
    .list-top-header li span{
        display: inline-block;
        width: 140px;
        color:#002442;
    }
    html, body {
        height:100%;
        width:100%;
        max-width:793px;
        margin:0 auto;
        padding:0;
        position: relative;
    }
    .footerLogoStyle{
        position: absolute;
        bottom:0;
        left:0;
        right:0;
        width:100%
    }
    img{
        max-width:100%;
    }

    .mainTitleInvoice {
        color: #09d1a3;
        font-size: 36px;
        margin: 0;
        font-weight: 600;
        padding-bottom: 8px;
        letter-spacing: 1px;
        padding-top: 15px;
    }
    .mainListSize:before,
    .mainListSize:after{
        content: '';
        display: table;
        clear: both;
    }
    .mainListSize {
        display: table;
        padding: 20px 0;
    }
    .leftSideInvoice {
        width: 100%;
        display: table-cell;
        padding-right: 10px
    }
    .rightSideInvoice {
        width: auto;
        display: table-cell;
    }
    .mainListSize ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    table.table thead th {
        font-weight: 600;
        color: #fff;
        background-color: #09d1a3;
    }
    .invoiceInnerTitle {
        color: #09d1a3;
        font-weight: 600;
        font-size: 16px;
        letter-spacing: 0.5px;
        margin: 0;
    }
    h4.invoiceInnerTitle {
        color: #09d1a3;
        font-weight: 600;
        font-size: 16px;
        letter-spacing: 0.5px;
        margin: 0;
    }

    h2.nameStyleInvoice {
        margin: 0 0 10px;
        font-size: 26px;
        font-weight: 600;
        color: #033b4c;
    }
    p.addressStyle {
        font-size: 14px;
        color: #5F6262;
        margin: 0;
    }
    .mainListSize ul > li {
        font-size: 14px;
        color: #5F6262;
        display: flex;
        margin: 5px 0;
    }
    .mainListSize ul > li > span {
        padding-right: 5px;
    }
    table tbody tr:nth-child(odd) td {
        background-color: #f7f8fa;
    }
    
</style>
</head>
<body>
    <div class="">
        <div class="">
            <div class="topHeader">
                <div class="logoStyle">
                    <img src="{{env('APP_URL')}}/images/submit-invoice-header-1.png" alt="Submitit">
                </div>
                <div class="sideHeader">
                    <h1 class="mainTitleInvoice">INVOICE</h1>
                    <div class="list-top-header">
                        <ul>
                            <li><span>Invoice Number:</span>{{$invoice['subscriptionId']}} </li>
                            <li><span>Invoice Date:</span> {{$invoice['created_at']}} </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="table-size-main">
                <div class="mainListSize">
                    <div class="leftSideInvoice">
                        <h4 class="invoiceInnerTitle">Invoice To:</h4>
                        <h2 class="nameStyleInvoice">{{$user['firstName']}} {{$user['lastName']}}</h2>
                        <p class="addressStyle">{{$user['street']}}, {{$user['city']}}, {{$user['state']}}, {{$user['country']}}, {{$user['zipCode']}}</p>
                        <ul>
                            <li><span>Phone Number:</span> {{$user['phone']}} </li>
                            <li><span>Email:</span> {{$user['email']}} </li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap table-bordered">
                        <thead>
                            <tr>
                                <th>Transaction Id</th>
                                <th>Packages</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$invoice['transactionId']}}</td>
                                <td>{{$invoice['packageName']}}</td>
                                <td>{{$invoice['startFrom']}}</td>
                                <td>{{$invoice['expiredAt']}}</td>
                                <td>${{$invoice['amount']}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr><td height="10"></td></tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">SubTotal :</td>
                                <td><strong>${{$invoice['amount']}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">Discount :</td>
                                <td><strong>-0%</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td colspan="2">Total :</td>
                                <td><strong>${{$invoice['amount']}}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="footerLogoStyle">
        <img src="{{env('APP_URL')}}/images/footer-1.png">
    </div>
</body>
</html>